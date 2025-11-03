<nav id="sidebar" aria-label="Main Navigation">
    <div class="content-header bg-white-5">
        <a class="font-w600 text-dual" href="{{ route('index') }}">
            <span class="smini-visible">
                <i class="fa fa-circle-notch text-primary"></i>
            </span>
            <span class="smini-hide font-size-h5 tracking-wider">{{ config('app.name') }}<span class="font-w400"></span>
            </span>
        </a>

        <div>
            <a class="d-lg-none btn btn-sm btn-dual ml-1" data-toggle="layout" data-action="sidebar_close"
                href="javascript:void(0)">
                <i class="fa fa-fw fa-times"></i>
            </a>
        </div>
    </div>
    <div class="js-sidebar-scroll">
        <div class="content-side">
            <ul class="nav-main">

                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->route()->getName() == 'index' ? 'active' : '' }}"
                        href="{{ route('index') }}">
                        <i class="nav-main-link-icon si si-speedometer"></i>
                        <span class="nav-main-link-name"><span class="rounded p-1 ">Dashboard</span></span>
                    </a>
                </li>

                @if ($user->checkPermission('lead.source.type.view'))
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->route()->getName() == 'admin.lead.source.type' ? 'active' : '' }}"
                            href="{{ route('admin.lead.source.type') }}">
                            <i class="nav-main-link-icon si si-paper-plane"></i>
                            <span class="nav-main-link-name"><span class="rounded p-1 ">Lead Source Type</span></span>
                        </a>
                    </li>
                @endif
                @if ($user->checkPermission('lead.add'))
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->route()->getName() == 'admin.create.lead' ? 'active' : '' }}"
                            href="{{ route('admin.create.lead') }}">
                            <i class="nav-main-link-icon fas fa-plus"></i>
                            <span class="nav-main-link-name"><span class="rounded p-1 ">Add New Lead</span></span>
                        </a>
                    </li>
                @endif
                @if ($user->checkPermission('lead.assign'))
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->route()->getName() == 'admin.lead_assign' ? 'active' : '' }}"
                            href="{{ route('admin.lead_assign') }}">
                            <i class="nav-main-link-icon fas fa-envelope"></i>
                            <span class="nav-main-link-name"><span class="rounded p-1 ">Lead Assign @new </span></span>
                        </a>
                    </li>
                @endif
                @if ($user->checkPermission('lead.view'))
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->route()->getName() == 'admin.all.lead.info' ? 'active' : '' }}"
                            href="{{ route('admin.all.lead.info', ['search' => '', 'status' => 'All', 'start_date' => '', 'end_date' => '', 'Submit' => 'Submit']) }}">
                            <i class="nav-main-link-icon fab fa-facebook-f"></i>
                            <span class="nav-main-link-name"><span class="rounded p-1 ">All Lead Info</span></span>
                        </a>
                    </li>
                @endif
                @if ($user->checkPermission('sale.add'))
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->route()->getName() == 'sale.create' ? 'active' : '' }}"
                            href="{{ route('sale.create') }}">
                            <i class="nav-main-link-icon si si-notebook"></i>
                            <span class="nav-main-link-name"><span class="rounded p-1 ">Add New Sale @new </span></span>
                        </a>
                    </li>
                @endif
                @if ($user->checkPermission('sale.view'))
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ in_array(request()->route()->getName(), ['sale.index', 'sale.edit']) ? 'active' : '' }}"
                            href="{{ route('sale.index') }}">
                            <i class="nav-main-link-icon si si-layers"></i>
                            <span class="nav-main-link-name"><span class="rounded p-1 ">Sales Report @new </span></span>
                        </a>
                    </li>
                @endif
                @if ($user->checkPermission('calendar.view'))
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->route()->getName() == 'calendar' ? 'active' : '' }}"
                            href="{{ route('calendar') }}">
                            <i class="nav-main-link-icon si si-settings"></i>
                            <span class="nav-main-link-name"><span class="rounded p-1 ">Inst. Calendar @new
                                </span></span>
                        </a>
                    </li>
                @endif
                @if ($user->checkPermission('bounce.view'))
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->route()->getName() == 'bounce' ? 'active' : '' }}"
                            href="{{ route('bounce', ['status' => 'Cancel']) }}">
                            <i class="nav-main-link-icon si si-settings"></i>
                            <span class="nav-main-link-name"><span class="rounded p-1 ">Bounce Report @new</span></span>
                        </a>
                    </li>
                @endif
                @if ($user->checkPermission('sms.view'))
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->route()->getName() == 'sms.create' ? '' : '' }}"
                            href="{{ route('sms.create') }}">
                            <i class="nav-main-link-icon si si-settings"></i>
                            <span class="nav-main-link-name"><span class="rounded p-1 ">Send SMS @new </span></span>
                        </a>
                    </li>
                @endif
                @if ($user->checkPermission('crm.view'))
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->route()->getName() == 'admin.crm' ? 'active' : '' }}"
                            href="{{ route('admin.crm') }}">
                            <i class="nav-main-link-icon fas fa-users-cog"></i>
                            <span class="nav-main-link-name"><span class="rounded p-1">CRM</span></span>
                        </a>
                    </li>
                @endif
                @if ($user->checkPermission('role.view'))
                    <li class="nav-main-item">
                        <a class="nav-main-link {{ request()->route()->getName() == 'crm.role.permission' ? 'active' : '' }}"
                            href="{{ route('crm.role.permission') }}">
                            <i class="nav-main-link-icon si si-settings"></i>
                            <span class="nav-main-link-name"><span class="rounded p-1 ">CRM Role &
                                    Permission</span></span>
                        </a>
                    </li>
                @endif




            </ul>
        </div>
    </div>
</nav>
