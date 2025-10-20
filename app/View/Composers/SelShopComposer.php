<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\User;

class ProfileComposer
{
    public function compose(View $view)
    {
        $user = User::find(1); // ��: ���[�U�[ID 1 �̃��[�U�[�f�[�^���擾
        $view->with('user', $user);
    }
}
