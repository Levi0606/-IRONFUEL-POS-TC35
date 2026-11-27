<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Users extends BaseController
{
    // DISPLAY STAFF ACCOUNTS
    public function index()
    {
        $model = new UserModel();

        return view('users/index', [
            'users' => $model->orderBy('id', 'DESC')->findAll()
        ]);
    }

    // SHOW ADD OR EDIT STAFF FORM
    public function form($id = null)
    {
        $model = new UserModel();

        $user = null;

        if ($id !== null) {
            $user = $model->find($id);

            if (!$user) {
                throw PageNotFoundException::forPageNotFound(
                    'Staff account not found.'
                );
            }
        }

        return view('users/form', [
            'user' => $user
        ]);
    }

    // CREATE OR UPDATE STAFF ACCOUNT
    public function save($id = null)
    {
        $model = new UserModel();

        $user = null;

        if ($id !== null) {
            $user = $model->find($id);

            if (!$user) {
                throw PageNotFoundException::forPageNotFound(
                    'Staff account not found.'
                );
            }
        }

        $username = trim(
            $this->request->getPost('username') ?? ''
        );

        // USERNAME MUST BE UNIQUE
        $usernameRule = 'required|max_length[50]';

        if ($id === null) {
            $usernameRule .= '|is_unique[users.username]';
        } elseif ($username !== $user['username']) {
            $usernameRule .= '|is_unique[users.username]';
        }

        $rules = [
            'username' => $usernameRule,
            'full_name' => 'required|max_length[100]',
            'email' => 'permit_empty|valid_email|max_length[100]',
            'password' => $id === null
                ? 'required|min_length[8]'
                : 'permit_empty|min_length[8]'
        ];

        $avatar = $this->request->getFile('avatar');

        // VALIDATE AVATAR IF UPLOADED
        if (
            $avatar !== null &&
            $avatar->getError() !== UPLOAD_ERR_NO_FILE
        ) {
            $rules['avatar'] =
                'uploaded[avatar]|' .
                'is_image[avatar]|' .
                'mime_in[avatar,image/jpeg,image/png]|' .
                'max_size[avatar,2048]';
        }

        if (!$this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // PREPARE STAFF DATA
        $data = [
            'username' => $username,
            'full_name' => trim(
                $this->request->getPost('full_name')
            ),
            'email' => trim(
                $this->request->getPost('email') ?? ''
            )
        ];

        // HASH PASSWORD ONLY WHEN PROVIDED
        $password = $this->request->getPost('password');

        if ($password !== null && $password !== '') {
            $data['password'] = password_hash(
                $password,
                PASSWORD_DEFAULT
            );
        }

        if ($id === null) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        $newName = null;
        $newPath = null;

        // PROCESS STAFF AVATAR
        if (
            $avatar !== null &&
            $avatar->isValid() &&
            !$avatar->hasMoved()
        ) {
            $folder = FCPATH . 'uploads/avatars/';

            if (!is_dir($folder)) {
                if (
                    !mkdir($folder, 0755, true) &&
                    !is_dir($folder)
                ) {
                    return redirect()
                        ->back()
                        ->withInput()
                        ->with(
                            'error',
                            'Unable to create avatar folder.'
                        );
                }
            }

            $newName = bin2hex(random_bytes(16)) . '.jpg';
            $newPath = $folder . $newName;

            try {
                service('image')
                    ->withFile($avatar->getTempName())
                    ->fit(300, 300)
                    ->convert(IMAGETYPE_JPEG)
                    ->save($newPath, 85);

                $data['avatar'] = $newName;

            } catch (\Throwable $e) {
                log_message(
                    'error',
                    'Avatar processing error: ' .
                    $e->getMessage()
                );

                if (is_file($newPath)) {
                    unlink($newPath);
                }

                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        'error',
                        'Avatar upload failed. Check PHP GD.'
                    );
            }
        }

        // SAVE TO DATABASE
        try {
            if ($id !== null) {
                $saved = $model->update($id, $data);
            } else {
                $saved = $model->insert($data);
            }

            if ($saved === false) {
                throw new \RuntimeException(
                    'Unable to save staff account.'
                );
            }

        } catch (\Throwable $e) {
            log_message(
                'error',
                'Staff save error: ' . $e->getMessage()
            );

            if ($newPath !== null && is_file($newPath)) {
                unlink($newPath);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to save staff account.'
                );
        }

        // DELETE PREVIOUS AVATAR AFTER SUCCESSFUL UPDATE
        if (
            $id !== null &&
            $newName !== null &&
            !empty($user['avatar'])
        ) {
            $oldPath = FCPATH .
                'uploads/avatars/' .
                basename($user['avatar']);

            if (is_file($oldPath)) {
                unlink($oldPath);
            }
        }

        // UPDATE CURRENT ADMIN SESSION DETAILS
        if (
            $id !== null &&
            (int) session()->get('user_id') === (int) $id
        ) {
            session()->set([
                'username' => $data['username'],
                'full_name' => $data['full_name']
            ]);
        }

        return redirect()
            ->to('/users')
            ->with(
                'success',
                'Staff account saved successfully!'
            );
    }

    // DELETE STAFF ACCOUNT
    public function delete($id)
    {
        $model = new UserModel();

        $user = $model->find($id);

        if (!$user) {
            throw PageNotFoundException::forPageNotFound(
                'Staff account not found.'
            );
        }

        // PREVENT DELETING CURRENT ADMIN
        if (
            (int) session()->get('user_id') === (int) $id
        ) {
            return redirect()
                ->to('/users')
                ->with(
                    'error',
                    'You cannot delete your own account.'
                );
        }

        // PROTECT STAFF REFERENCED BY SALES
        $hasSales = db_connect()
            ->table('sales')
            ->where('sold_by', $id)
            ->countAllResults() > 0;

        if ($hasSales) {
            return redirect()
                ->to('/users')
                ->with(
                    'error',
                    'Cannot delete staff with sales history.'
                );
        }

        try {
            $deleted = $model->delete($id);

            if ($deleted === false) {
                throw new \RuntimeException(
                    'Staff deletion failed.'
                );
            }

        } catch (\Throwable $e) {
            log_message(
                'error',
                'Staff delete error: ' .
                $e->getMessage()
            );

            return redirect()
                ->to('/users')
                ->with(
                    'error',
                    'Unable to delete staff.'
                );
        }

        // REMOVE OLD AVATAR
        if (!empty($user['avatar'])) {
            $path = FCPATH .
                'uploads/avatars/' .
                basename($user['avatar']);

            if (is_file($path)) {
                unlink($path);
            }
        }

        return redirect()
            ->to('/users')
            ->with(
                'success',
                'Staff deleted successfully!'
            );
    }
}
