<div class="scrollbar-sidebar">
    <div class="app-sidebar__inner" style="overflow-y: scroll; height: 100%">
        <ul class="vertical-nav-menu">
            <!---------------------     Start Dashbroad section     --------------------->
            <li class="app-sidebar__heading">Dashboard</li>
            <!--            --><?php //dd(request()->routeIs('home'))
            ?>
            <li>
                <a class="{{ request()->routeIs('admin/home') ? 'mm-active' : '' }}" href="{{ route('admin.home') }}">
                    <i class="metismenu-icon pe-7s-rocket"></i>
                    Dashboard
                </a>
            </li>
            <li class="app-sidebar__heading">Đề kiểm tra</li>
            <li>
                <a class="{{ request()->is(['admin/exam-administration']) ? 'mm-active' : '' }}"
                   href="{{ route('admin.exam-list') }}">
                    <i class=" metismenu-icon fa-solid fa-chart-simple"></i>
                    Quản lý đề kiểm tra
                </a>
            </li>
            @can('Level_List')
            <li class="app-sidebar__heading">Cấp độ bài học</li>
            <li>
                <a class="{{ request()->is(['admin/level']) ? 'mm-active' : '' }}"
                   href="{{ route('admin.level-list') }}">
                    <i class=" metismenu-icon fa-solid fa-chart-simple"></i>
                    Quản lý cấp độ bài học
                </a>
            </li>
            @endcan
            
            @can('Lesson_List')
            <li class="app-sidebar__heading">Bài học</li>
            <li>
                <a class="{{ request()->is(['admin/lesson']) ? 'mm-active' : '' }}"
                   href="{{ route('admin.lesson-list') }}">
                    <i class="metismenu-icon fa-solid fa-swatchbook"></i>
                    Quản lý bài học
                </a>
            </li>
            @endcan
            {{-- Start Testing --}}
            @if(Gate::check('Vocabulary_List') || Gate::check('Question_Vocabulary_List'))
            <li class="app-sidebar__heading">Đề từ vựng</li>
            @endif
            @can('Vocabulary_List')
            <li>
                <a class="{{ request()->is(['admin/volabulary-level-test']) ? 'mm-active' : '' }}"
                   href="{{ route('admin.vocabulary-list') }}">
                    <i class="metismenu-icon fa-solid fa-laptop-file"></i>
                    Quản lý đề
                </a>
            </li>
            @endcan
            @if(Gate::check('Listening_List') || Gate::check('Question_Listening_List'))
            <li class="app-sidebar__heading">Nghe</li>
            @endif
            @can('Listening_List')
            <li>
                <a class="{{ request()->is(['admin/topic-listening-level-test']) ? 'mm-active' : '' }}"
                   href="{{ route('admin.topic-listening-list') }}">
                    <i class="metismenu-icon fa-solid fa-laptop-file"></i>
                    Quản lý đề
                </a>
            </li>
            @endcan
            @can('Question_Listening_List')
            <li>
                <a class="{{ request()->is(['admin/listening-level-test/question-list']) ? 'mm-active' : '' }}"
                   href="{{ route('admin.listening-question-list') }}">
                    <i class="metismenu-icon fa-solid fa-clipboard-question"></i>
                    Quản lý âm thanh và câu hỏi
                </a>
            </li>
            @endcan
            @if(Gate::check('Reading_List'))
            <li class="app-sidebar__heading">Đọc</li>
            @endif
            @can('Reading_List')
            <li>
                <a class="{{ request()->is(['admin/reading-level-test/topic-list']) ? 'mm-active' : '' }}"
                   href="{{ route('admin.reading-topic-list') }}">
                    <i class="metismenu-icon fa-solid fa-laptop-file"></i>
                    Quản lý đề
                </a>
            </li>
            @endcan
            @if(Gate::check('Grammar_List') || Gate::check('Question_Grammar_List'))
            <li class="app-sidebar__heading">Ngữ pháp</li>
            @endif
            @can('Grammar_List')

            <li>
                <a class="{{ request()->is(['admin/grammar-level-test']) ? 'mm-active' : '' }}"
                   href="{{ route('admin.grammar-list') }}">
                    <i class="metismenu-icon fa-solid fa-laptop-file"></i>
                    Quản lý đề
                </a>
            </li>
            @endcan
            @can('Question_Grammar_List')
            <li>
                <a class="{{ request()->is(['admin/grammar-level-test/question-list']) ? 'mm-active' : '' }}"
                   href="{{ route('admin.grammar-question-list') }}">
                    <i class="metismenu-icon fa-solid fa-clipboard-question"></i>
                    Quản lý câu hỏi
                </a>
            </li>
            @endcan
            <li class="app-sidebar__heading">Luyện âm</li>
            <li>
                <a class="{{ request()->is(['admin/speaking-level-test']) ? 'mm-active' : '' }}"
                   href="{{ route('admin.speak-list') }}">
                    <i class="metismenu-icon fa-solid fa-laptop-file"></i>
                    Quản lý đề trắc nghiệm
                </a>
            </li>
            <li>
                <a class="{{ request()->is(['admin/speaking-level-test/question-list']) ? 'mm-active' : '' }}"
                   href="{{ route('admin.speak-question-list') }}">
                    <i class="metismenu-icon fa-solid fa-clipboard-question"></i>
                    Câu hỏi dạng trắc nghiệm
                </a>
            </li>
            <li>
                <a class="{{ request()->is(['admin/speaking-level-test/question-luyen-am']) ? 'mm-active' : '' }}"
                   href="{{ route('admin.speak-question-luyen-am') }}">
                    <i class="metismenu-icon fa-solid fa-clipboard-question"></i>
                    Luyện âm theo chủ đề
                </a>
            </li>
            @if(Gate::check('User_List') || Gate::check('Role_List'))
            <li class="app-sidebar__heading">Người dùng</li>
            @endif
            @can('User_List')
            <li>
                <a class="{{ request()->is(['admin/users']) ? 'mm-active' : '' }}"
                   href="{{ route('admin.user-list') }}">
                    <i class="metismenu-icon fa-regular fa-user"></i>
                    {{Auth::user()->is_admin == 1 ? 'Quản lý người dùng' : 'Xem lịch sử kiểm tra'}}
                    
                </a>
            </li>
            @endcan
            @can('Role_List')
            <li>
                <a class="{{ request()->is(['admin/roles']) ? 'mm-active' : '' }}"
                   href="{{ route('admin.role-list') }}">
                    <i class="metismenu-icon fa-solid fa-users"></i>
                    Quản lý vai trò
                </a>
            </li>
            @endcan
            <li>
            <li>
                {{--                <a class="{{ request()->is(['newsletters','newsletters/*']) ? 'mm-active' : '' }}" href="{{route('newsletter-home')}}"> --}}
                {{--                    <i class="metismenu-icon pe-7s-paper-plane"></i> --}}
                {{--                    Listening --}}
                {{--                </a> --}}
                {{--            </li> --}}
                {{-- <!---------------------     Start Application section     --------------------->
                <li class="app-sidebar__heading">Applications</li>
                <li>
                    <a class="{{ request()->is(['campaigns', 'campaigns/*']) ? 'mm-active' : '' }}"  href="{{route('campaign-home')}}">
                        <i class="metismenu-icon lnr-bullhorn lnr"></i>
                        Campaigns
                    </a>
                </li>
                <li>
                    <a class="{{ request()->is(['newsletters','newsletters/*']) ? 'mm-active' : '' }}" href="{{route('newsletter-home')}}">
                        <i class="metismenu-icon pe-7s-paper-plane"></i>
                        Newsletters
                    </a>
                </li>
                <li>
                    <a class="{{ request()->is(['customer-winback', 'customer-winback/*']) ? 'mm-active' : '' }}" href="{{route('winback-home')}}">
                        <i class="metismenu-icon pe-7s-mail-open-file"></i>
                        Customer Winback
                    </a>
                </li>

                <li>
                    <a class="{{ request()->is(['email-template', 'email-template/*']) ? 'mm-active' : '' }}" href="{{route('eTemplate-home')}}">
                        <i class="metismenu-icon pe-7s-news-paper"></i>
                        Email Templates
                    </a>
                </li>
                <li>
                    <a class="{{ request()->is(['coupon', 'coupon/*']) ? 'mm-active' : '' }}" href="{{route('coupon-home')}}">
                        <i class="metismenu-icon pe-7s-ticket"></i>
                        Coupons
                    </a>
                </li>
                <li>
                    <a class="{{ request()->is(['customer','customer/*']) ? 'mm-active' : '' }}" href="{{route('customer')}}">
                        <i class="metismenu-icon pe-7s-add-user"></i>
                        Customers
                    </a>
                </li>
                <li>
                    <a class="{{ request()->is('report') ? 'mm-active' : '' }}" href="{{route('report')}}">
                        <i class="metismenu-icon pe-7s-graph3"></i>
                        Reports
                    </a>
                </li>
                <!---------------------     End Application section           --------------------->
                <!---------------------     Start Account setting section     --------------------->
                <li class="app-sidebar__heading">Account Settings</li>
                <li>
                    <a href="#">
                        <i class="metismenu-icon pe-7s-user"></i>
                        Account Infomation
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul>
                        <li>
                            <a class="{{ request()->is('settings/my-info') ? 'mm-active' : '' }}" href="{{route('my-info')}}">
                                <i class="metismenu-icon "></i>
                                My Infomation
                            </a>
                        </li>
                        <li>
                            <a class="{{ request()->is('settings/my-plan') ? 'mm-active' : '' }}" href="{{route('my-plan')}}">
                                <i class="metismenu-icon"></i>
                                My Plan & Billing
                            </a>
                        </li>
                        <li>
                            <a class="{{ request()->is(['settings/plans', 'settings/plans/*']) ? 'mm-active' : '' }}" href="{{route('plan')}}">
                                <i class="metismenu-icon"></i>
                                Plans
                            </a>
                        </li>
                    </ul>
                </li>
                <!---------------------     End Account settings section           --------------------->
                <!---------------------     Start Email setting section           --------------------->
                <li>
                    <a href="#">
                        <i class="metismenu-icon pe-7s-mail"></i>
                        Email Settings
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul>
                        <li>
                            <a class="{{ request()->is('settings/email') ? 'mm-active' : '' }}" href="{{route('sending-email')}}">
                                <i class="metismenu-icon"></i>
                                Sender Options
                            </a>
                        </li>
                        <li>
                            <a class="{{ request()->is(['settings/sending-domain', 'settings/sending-domain/*']) ? 'mm-active' : '' }}" href="{{route('sending-domain')}}">
                                <i class="metismenu-icon"></i>
                                Sending Domain
                            </a>
                        </li>
                    </ul>
                </li>
                <!---------------------     End Email settings section           --------------------->
                <!---------------------     Start Business Information section           --------------------->
                <li>
                    <a class="{{ request()->is('settings/business-information') ? 'mm-active' : '' }}" href="{{route('business-information')}}">
                        <i class="metismenu-icon lnr-store"></i>
                        Business Information
                    </a>
                </li>
                <li class="app-sidebar__heading">3rd party (coming soon)</li>
                <li class="app-sidebar__heading">Support</li>
                <li>
                    <a href="#">
                        <i class="metismenu-icon pe-7s-help2"></i>
                        Help center
                    </a>
                </li> --}}
                <!---------------------     End Support settings section           --------------------->

        </ul>
    </div>
</div>
