<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Backend\Admin\Permission;
use App\Models\Backend\Admin\Role;
use App\Models\Backend\Admin\Admin;
use App\Models\Backend\Website;
use Faker\Provider\Uuid;
class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		// 清空表
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('admin_model_has_permissions')->truncate();
        DB::table('admin_model_has_roles')->truncate();
        DB::table('admin_role_has_permissions')->truncate();
        DB::table('admin_users')->truncate();
        DB::table('admin_roles')->truncate();
        DB::table('admin_permissions')->truncate();
		DB::table('siteconfig')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
		
		// 用户
        $website = Website::create([
            'site_name'  => '我的站点',
        ]);
		
		
		// 用户
        $user = Admin::create([
            'username'  => 'root',
            'phone'     => '18908221080',
            'name'      => '超级管理员',
            'email'     => 'sorshion@gmail.com',
            'password'  => bcrypt('123456'),
            'uuid'      => Uuid::uuid(),
        ]);

        // 角色
        $role = Role::create([
            'name' => 'root',
            'display_name' => '超级管理员',
        ]);
		// 权限
        $permissions = [
            [
                'name' => 'system',
                'display_name' => '系统管理',
                'route' => '',
                'icon' => '100',
                'status' => '1',
                'child' => [
                    [
                        'name' => 'system.manage',
                        'display_name' => '后台用户',
                        'route' => '',
                        'icon' => 'fa-user-secret',
                        'status' => '1',
                        'child' => [
                            [
                                'name' => 'system.admin',
                                'display_name' => '用户管理',
                                'route' => 'admin.admin',
                                'icon' => 'fa-user-plus',
                                'status' => '1',
                                'child' => [
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'system.admin.create', 'display_name' => '添加用户', 'route' => 'admin.admin.store'],
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'system.admin.edit', 'display_name' => '编辑用户', 'route' => 'admin.admin.update'],
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'system.admin.destroy', 'display_name' => '删除用户', 'route' => 'admin.admin.destroy'],
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'system.admin.role', 'display_name' => '分配角色', 'route' => 'admin.admin.assignRole'],
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'system.admin.permission', 'display_name' => '分配权限', 'route' => 'admin.admin.assignPermission'],
                                ]
                            ],
                            [
                                'name' => 'system.role',
                                'display_name' => '角色管理',
                                'route' => 'admin.role',
                                'icon' => 'fa-users',
                                'status' => '1', 
                                'child' => [
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'system.role.create', 'display_name' => '添加角色', 'route' => 'admin.role.store'],
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'system.role.edit', 'display_name' => '编辑角色', 'route' => 'admin.role.update'],
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'system.role.destroy', 'display_name' => '删除角色', 'route' => 'admin.role.destroy'],
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'system.role.permission', 'display_name' => '分配权限', 'route' => 'admin.role.assignPermission'],
                                ]
                            ],
                            [
                                'name' => 'system.permission',
                                'display_name' => '权限管理',
                                'route' => 'admin.permission',
                                'icon' => 'fa-key',
                                'status' => '1', 
                                'child' => [
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'system.permission.create', 'display_name' => '添加权限', 'route' => 'admin.permission.store'],
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'system.permission.edit', 'display_name' => '编辑权限', 'route' => 'admin.permission.update'],
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'system.permission.destroy', 'display_name' => '删除权限', 'route' => 'admin.permission.destroy'],
                                ]
                            ],
                            
                        ]
                    ],
                    [
                        'name' => 'system.log',
                        'display_name' => '操作日志',
                        'route' => 'admin.log',
                        'icon' => 'fa-thumb-tack',
                        'status' => '1', 
                        'child' => [
                            ['icon' => 'fa-edit', 'status' => '0', 'name' => 'system.log.create', 'display_name' => '添加日志', 'route' => 'admin.log.store'],
                            ['icon' => 'fa-edit', 'status' => '0', 'name' => 'system.log.edit', 'display_name' => '编辑日志', 'route' => 'admin.log.update'],
                            ['icon' => 'fa-edit', 'status' => '0', 'name' => 'system.log.destroy', 'display_name' => '删除日志', 'route' => 'admin.log.destroy'],
                        ]
                    ],
                    [
                        'name' => 'system.sql',
                        'display_name' => '数据备份',
                        'route' => 'system.sql',
                        'icon' => 'fa-database',
                        'status' => '1', 
                        'child' => [
                            ['icon' => 'fa-stack-overflow', 'status' => '0', 'name' => 'system.sql.store', 'display_name' => '数据备份', 'route' => 'system.sql.store'],
                            ['icon' => 'fa-upload', 'status' => '0', 'name' => 'system.sql.recover', 'display_name' => '数据恢复', 'route' => 'system.sql.recover'],
                            ['icon' => 'fa-cloud-download', 'status' => '0', 'name' => 'system.sql.download', 'display_name' => '下载数据', 'route' => 'system.sql.download'],
                            ['icon' => 'fa-window-close', 'status' => '0', 'name' => 'system.sql.destroy', 'display_name' => '删除数据', 'route' => 'system.sql.destroy'],
                        ]
                    ],
                ]
            ],
            [
                'name' => 'site',
                'display_name' => '站点数据',
                'route' => '',
                'icon' => 'fa-sitemap',
                'status' => '1', 
                'child' => [
                    [
                        'name' => 'site.menu',
                        'display_name' => '菜单管理',
                        'route' => '',
                        'icon' => 'fa-sitemap',
                        'status' => '1', 
                        'child' => [
                            [
                                'name' => 'site.nav',
                                'display_name' => '导航管理',
                                'route' => 'site.nav',
                                'icon' => 'fa-navicon',
                                'status' => '1', 
                                'child' => [
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'site.nav.create', 'display_name' => '添加导航', 'route' => 'site.nav.store'],
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'site.nav.edit', 'display_name' => '编辑导航', 'route' => 'site.nav.update'],
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'site.nav.destroy', 'display_name' => '删除导航', 'route' => 'site.nav.destroy'],
                                ]
                            ],
                            [
                                'name' => 'site.tag',
                                'display_name' => '标签管理',
                                'route' => 'site.tag',
                                'icon' => 'fa-tag',
                                'status' => '1', 
                                'child' => [
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'site.tag.create', 'display_name' => '添加标签', 'route' => 'site.tag.store'],
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'site.tag.edit', 'display_name' => '编辑标签', 'route' => 'site.tag.update'],
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'site.tag.destroy', 'display_name' => '删除标签', 'route' => 'site.tag.destroy'],
                                ]
                            ],
                        ]
                    ],
                    [
                        'name' => 'site.content',
                        'display_name' => '内容管理',
                        'route' => '',
                        'icon' => 'fa-book',
                        'status' => '1', 
                        'child' => [
                            [
                                'name' => 'site.column',
                                'display_name' => '栏目单页',
                                'route' => 'site.column',
                                'icon' => 'fa-columns',
                                'status' => '1', 
                                'child' => [
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'site.column.create', 'display_name' => '添加栏目', 'route' => 'site.column.store'],
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'site.column.edit', 'display_name' => '编辑栏目', 'route' => 'site.column.update'],
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'site.column.destroy', 'display_name' => '删除栏目', 'route' => 'site.column.destroy'],
                                ]
                            ],
                            [
                                'name' => 'site.article',
                                'display_name' => '文章管理',
                                'route' => 'site.article',
                                'icon' => 'fa-file-word-o',
                                'status' => '1', 
                                'child' => [
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'site.article.create', 'display_name' => '添加文章', 'route' => 'site.article.store'],
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'site.article.edit', 'display_name' => '编辑文章', 'route' => 'site.article.update'],
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'site.article.destroy', 'display_name' => '删除文章', 'route' => 'site.article.destroy'],
                                ]
                            ],
                            [
                                'name' => 'site.review',
                                'display_name' => '评论管理',
                                'route' => 'site.review',
                                'icon' => 'fa-file-audio-o',
                                'status' => '1', 
                                'child' => [
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'site.review.create', 'display_name' => '添加评论', 'route' => 'site.review.store'],
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'site.review.edit', 'display_name' => '编辑评论', 'route' => 'site.review.update'],
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'site.review.destroy', 'display_name' => '删除评论', 'route' => 'site.review.destroy'],
                                ]
                            ],
                        ]
                    ],
                    [
                        'name' => 'site.banner',
                        'display_name' => '轮播banner',
                        'route' => 'site.banner',
                        'icon' => 'fa-film',
                        'status' => '1', 
                        'child' => [
                            ['icon' => 'fa-edit', 'status' => '0', 'name' => 'site.banner.create', 'display_name' => '添加banner', 'route' => 'site.banner.store'],
                            ['icon' => 'fa-edit', 'status' => '0', 'name' => 'site.banner.edit', 'display_name' => '编辑banner', 'route' => 'site.banner.update'],
                            ['icon' => 'fa-edit', 'status' => '0', 'name' => 'site.banner.destroy', 'display_name' => '删除banner', 'route' => 'site.banner.destroy'],
                        ]

                    ],
                    [
                        'name' => 'site.images',
                        'display_name' => '图片附件',
                        'route' => 'site.image',
                        'icon' => 'fa-file-image-o',
                        'status' => '1', 
                        'child' => [
                            [
                                'name' => 'site.image',
                                'display_name' => '图片管理',
                                'route' => 'site.image',
                                'icon' => 'fa-file-image-o',
                                'status' => '1', 
                                'child' => [
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'site.image.create', 'display_name' => '添加图片', 'route' => 'site.image.store'],
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'site.image.edit', 'display_name' => '编辑图片', 'route' => 'site.image.update'],
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'site.image.destroy', 'display_name' => '删除图片', 'route' => 'site.image.destroy'],
                                ]
                            ],
                            [
                                'name' => 'site.images.destroy',
                                'display_name' => '删除临时',
                                'route' => 'site.images.destroy',
                                'icon' => 'fa-file-image-o',
                                'status' => '1' 
                            ],
                        ]
                    ],
                    
                ]
            ],
            [
                'name' => 'website',
                'display_name' => '站点管理',
                'route' => '',
                'icon' => 'fa-desktop',
                'status' => '1', 
                'child' => [
                    [
                        'name' => 'website.users',
                        'display_name' => '会员管理',
                        'route' => '',
                        'icon' => 'fa-street-view',
                        'status' => '1', 
                        'child' => [
                            [
                                'name' => 'website.user',
                                'display_name' => '会员列表',
                                'route' => 'website.user',
                                'icon' => 'fa-male',
                                'status' => '1', 
                                'child' => [
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'website.user.create', 'display_name' => '添加会员', 'route' => 'website.user.store'],
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'website.user.edit', 'display_name' => '编辑会员', 'route' => 'website.user.update'],
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'website.user.destroy', 'display_name' => '删除会员', 'route' => 'website.user.destroy'],
                                ]
                            ],
                            [
                                'name' => 'website.grade',
                                'display_name' => '等级管理',
                                'route' => 'website.grade',
                                'icon' => 'fa-cubes',
                                'status' => '1', 
                                'child' => [
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'website.grade.create', 'display_name' => '添加等级', 'route' => 'website.grade.store'],
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'website.grade.edit', 'display_name' => '编辑等级', 'route' => 'website.grade.update'],
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'website.grade.destroy', 'display_name' => '删除等级', 'route' => 'website.grade.destroy'],
                                ]
                            ],
                            [
                                'name' => 'website.point',
                                'display_name' => '积分管理',
                                'route' => 'website.point',
                                'icon' => 'fa-money',
                                'status' => '1', 
                                'child' => [
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'website.point.create', 'display_name' => '添加积分', 'route' => 'website.point.store'],
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'website.point.edit', 'display_name' => '编辑积分', 'route' => 'website.point.update'],
                                    ['icon' => 'fa-edit', 'status' => '0', 'name' => 'website.point.destroy', 'display_name' => '删除积分', 'route' => 'website.point.destroy'],
                                ]
                            ],
                        ]
                    ],
                    [
                        'name' => 'website.site',
                        'display_name' => '网站设置',
                        'route' => 'website.site',
                        'icon' => 'fa-laptop',
                        'status' => '1', 
                        'child' => [
                            ['icon' => 'fa-edit', 'status' => '0', 'name' => 'website.site.edit', 'display_name' => '编辑站点', 'route' => 'website.site.update'],
                        ]
                    ],
                    [
                        'name' => 'website.link',
                        'display_name' => '友情链接',
                        'route' => 'website.link',
                        'icon' => 'fa-link',
                        'status' => '1', 
                        'child' => [
                            ['icon' => 'fa-edit', 'status' => '0', 'name' => 'website.link.create', 'display_name' => '添加链接', 'route' => 'website.link.store'],
                            ['icon' => 'fa-edit', 'status' => '0', 'name' => 'website.link.edit', 'display_name' => '编辑链接', 'route' => 'website.link.update'],
                            ['icon' => 'fa-edit', 'status' => '0', 'name' => 'website.link.destroy', 'display_name' => '删除链接', 'route' => 'website.link.destroy'],
                        ]
                    ],
                    [
                        'name' => 'website.oauth',
                        'display_name' => '第三方登录',
                        'route' => 'website.oauth',
                        'icon' => 'fa-cogs',
                        'status' => '1', 
                        'child' => [
                            ['icon' => 'fa-edit', 'status' => '0', 'name' => 'website.oauth.create', 'display_name' => '添加第三方', 'route' => 'website.oauth.store'],
                            ['icon' => 'fa-edit', 'status' => '0', 'name' => 'website.oauth.edit', 'display_name' => '编辑第三方', 'route' => 'website.oauth.update'],
                            ['icon' => 'fa-edit', 'status' => '0', 'name' => 'website.oauth.destroy', 'display_name' => '删除第三方', 'route' => 'website.oauth.destroy'],
                        ]
                    ],
                ]
            ],
            
        ];

        foreach ($permissions as $pem1) {
            // 生成一级权限
            $p1 = Permission::create([
                'name' => $pem1['name'],
                'display_name' => $pem1['display_name'],
                'route' => $pem1['route'] ?? 1,
                'icon' => $pem1['icon'] ?? 1,
                'status' => $pem1['status'] ?? 0,
            ]);
            // 为角色添加权限
            $role->givePermissionTo($p1);
            // 为用户添加权限
            $user->givePermissionTo($p1);
            if (isset($pem1['child'])) {
                foreach ($pem1['child'] as $pem2) {
                    // 生成二级权限
                    $p2 = Permission::create([
                        'name' => $pem2['name'],
                        'display_name' => $pem2['display_name'],
                        'pid' => $p1->id,
                        'route' => $pem2['route'] ?? 1,
                        'icon' => $pem2['icon'] ?? 1,
                        'status' => $pem2['status'] ?? 0,
                    ]);
                    // 为角色添加权限
                    $role->givePermissionTo($p2);
                    // 为用户添加权限
                    $user->givePermissionTo($p2);
                    if (isset($pem2['child'])) {
                        foreach ($pem2['child'] as $pem3) {
                            // 生成三级权限
                            $p3 = Permission::create([
                                'name' => $pem3['name'],
                                'display_name' => $pem3['display_name'],
                                'pid' => $p2->id,
                                'route' => $pem3['route'] ?? '',
                                'icon' => $pem3['icon'] ?? 1,
                                'status' => $pem3['status'] ?? 0,
                            ]);
                            // 为角色添加权限
                            $role->givePermissionTo($p3);
                            // 为用户添加权限
                            $user->givePermissionTo($p3);
                            if (isset($pem3['child'])) {
                                foreach ($pem3['child'] as $pem4) {
                                    // 生成三级权限
                                    $p4 = Permission::create([
                                        'name' => $pem4['name'],
                                        'display_name' => $pem4['display_name'],
                                        'pid' => $p3->id,
                                        'route' => $pem4['route'] ?? '',
                                        'icon' => $pem4['icon'] ?? 1,
                                        'status' => $pem4['status'] ?? 0,
                                    ]);
                                    // 为角色添加权限
                                    $role->givePermissionTo($p4);
                                    // 为用户添加权限
                                    $user->givePermissionTo($p4);
                                }
                            }
                        }
                    }
                }
            }
        }

        // 为用户添加角色
        $user->assignRole($role);

        // 初始化的角色
        $roles = [
            ['name' => 'admin', 'display_name' => '管理员'],
        ];
        foreach($roles as $role) {
            Role::create($role);
        }
		
    }
}
	

