<?php

use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Admin;
use App\BotPro\Update;
use Dcat\Admin\Grid\Filter;
use Dcat\Admin\Layout\Menu;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;

/**
 * Dcat-admin - admin builder based on Laravel.
 * @author jqh <https://github.com/jqhph>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 *
 * extend custom field:
 * Dcat\Admin\Form::extend('php', PHPEditor::class);
 * Dcat\Admin\Grid\Column::extend('php', PHPEditor::class);
 * Dcat\Admin\Grid\Filter::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */
Dcat\Admin\Color::extend('pink', [
    'primary'        => '#FFC0CB',
    'primary-darker' => '#FFC0CB',
    'link'           => '#FFC0CB',
]);

Admin::menu(function (Menu $menu) {
    $menu->add([
        [
            'id'            => 1, // 此id只要保证当前的数组中是唯一的即可
            'title'         => '系统',
            'icon'          => 'feather icon-layers',
            'uri'           => 'setting',
            'parent_id'     => 0,
            'roles'         => 'administrator', // 与角色绑定
        ],
        [
            'id'            => 2, // 此id只要保证当前的数组中是唯一的即可
            'title'         => '核心授权',
            'icon'          => 'fa fa-fw fa-code-fork',
            'uri'           => 'BotCore',
            'parent_id'     => 0,
            'roles'         => 'administrator', // 与角色绑定
        ],
        [
            'id'            => 3, // 此id只要保证当前的数组中是唯一的即可
            'title'         => '插件管理',
            'icon'          => 'feather icon-cpu',
            'uri'           => 'Plugin',
            'parent_id'     => 0,
            'roles'         => 'administrator', // 与角色绑定
        ],
        [
            'id'            => 4, // 此id只要保证当前的数组中是唯一的即可
            'title'         => '设置',
            'icon'          => '',
            'uri'           => 'setting',
            'parent_id'     => 1,
            'roles'         => 'administrator', // 与角色绑定
        ]
    ]);
});

if(!request()->is(config('admin.route.prefix').'/update*')){
    if (!Cache::get('admin.botpro.update', null)) {
        if (Update::check()) {
            $r = config('admin.route.prefix');
            Admin::script(
                <<<JS
                    Dcat.confirm('程序有更新，是否跳转查阅？', null, function () {
                        location.href="/{$r}/update";
                    });
                JS
            );
        }
        Cache::put('admin.botpro.update', 1,84600);
    }
}

