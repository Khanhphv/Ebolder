<div class="scrollbar-sidebar">
    <div class="app-sidebar__inner" style="overflow-y: scroll; height: 100%">
        <ul class="vertical-nav-menu">
            @can('Exam_List')
                <li class="app-sidebar__heading">Tests</li>
                <li class="mm-parent">
                    <a class="{{ request()->is(['admin/exam-administration']) ? 'mm-active' : '' }}"
                    href="{{ route('admin.exam-list') }}">
                        <i class=" metismenu-icon fa-solid fa-chart-simple"></i>
                        Tests Management
                    </a>
                </li>
            @endcan
            @can('Level_List')
                <li class="app-sidebar__heading">Levels</li>
                <li class="mm-parent">
                    <a class="{{ request()->is(['admin/level']) ? 'mm-active' : '' }}"
                        href="{{ route('admin.level-list') }}">
                        <i class=" metismenu-icon fa-solid fa-chart-simple"></i>
                        Level management
                    </a>
                </li>
            @endcan
            <li class="app-sidebar__heading">Topics</li>
            {{-- Vocabulary --}}
            @if (Gate::check('Vocabulary_List') || Gate::check('Question_Vocabulary_List'))
                <li class="mm-parent">
                    <a href="#" aria-expanded="false">
                        <i class="metismenu-icon fa-solid fa-gears"></i>
                        Vocabulary
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul class="mm-collapse">
                        @can('Vocabulary_List')
                            @php
                                $isActiveVocab = request()->is(['admin/volabulary-level-test', 
                                'admin/volabulary-level-test/question-create',
                                'admin/volabulary-level-test/detail/*']);
                            @endphp
                            <li>
                                <a class="{{ $isActiveVocab ? 'mm-active' : '' }}"
                                    href="{{ route('admin.vocabulary-list') }}">
                                    <i class="metismenu-icon fa-solid fa-laptop-file"></i>
                                    Topics Management
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif
            {{-- Pronunciation  --}}
            @if (Gate::check('Talking_List'))
                <li class="mm-parent">
                    <a href="#" aria-expanded="false">
                        <i class="metismenu-icon fa-solid fa-microphone"></i>
                        Pronunciation
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul class="mm-collapse">
                        @php
                            $isActivePronun = request()->is(['admin/pronunciation-level-test/topic-list', 
                            'admin/pronunciation-level-test/topic-create',
                            'admin/pronunciation-level-test/topic-detail/*']);
                        @endphp
                        <li>
                            <a class="{{ $isActivePronun ? 'mm-active' : '' }}"
                                href="{{ route('admin.pronunciation-topic-list') }}">
                                <i class="metismenu-icon fa-solid fa-laptop-file"></i>
                                Topics Management
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
            {{-- Grammar --}}
            @if (Gate::check('Grammar_List') || Gate::check('Question_Grammar_List'))
                <li class="mm-parent">
                    <a href="#" aria-expanded="false">
                        <i class="metismenu-icon fa-solid fa-spell-check"></i>
                        Grammar
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul class="mm-collapse">
                        @can('Grammar_List')
                            @php
                                $isActiveGrammar = request()->is(['admin/grammar-level-test', 
                                'admin/grammar-level-test/question-create',
                                'admin/grammar-level-test/detail/*']);
                            @endphp
                            <li>
                                <a class="{{ $isActiveGrammar ? 'mm-active' : '' }}"
                                    href="{{ route('admin.grammar-list') }}">
                                    <i class="metismenu-icon fa-solid fa-laptop-file"></i>
                                    Topics Management
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif
            {{-- Listening --}}
            @if (Gate::check('Listening_List') || Gate::check('Question_Listening_List'))
                <li class="mm-parent">
                    <a href="#" aria-expanded="false">
                        <i class="metismenu-icon fa-solid fa-ear-listen"></i>
                        Listening
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul class="mm-collapse">
                        @can('Listening_List')
                            @php
                                $isActiveTopicListening = request()->is(['admin/topic-listening-level-test', 
                                'admin/detail-topic-listening/*']);
                                $isActiveQuestionListening = request()->is(['admin/listening-level-test/question-list', 
                                'admin/listening-level-test/question-edit/*']);
                            @endphp
                            <li>
                                <a class="{{ $isActiveTopicListening ? 'mm-active' : '' }}"
                                    href="{{ route('admin.topic-listening-list') }}">
                                    <i class="metismenu-icon fa-solid fa-laptop-file"></i>
                                    Topics Management
                                </a>
                            </li>
                        @endcan
                        @can('Question_Listening_List')
                            <li>
                                <a class="{{ $isActiveQuestionListening ? 'mm-active' : '' }}"
                                    href="{{ route('admin.listening-question-list') }}">
                                    <i class="metismenu-icon fa-solid fa-clipboard-question"></i>
                                    Question Management
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif
            {{-- Speaking --}}
            @if (Gate::check('Speaking_List') || Gate::check('Question_Speaking_List'))
                <li class="mm-parent">
                    <a href="#" aria-expanded="false">
                        <i class="metismenu-icon fa-solid fa-head-side-cough"></i>
                        Speaking
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul class="mm-collapse">
                        @php
                            $isActiveSpeaking = request()->is(['admin/speaking-level-test', 
                            'admin/speaking-level-test/question-create',
                            'admin/speaking-level-test/detail/*']);
                        @endphp
                        @can('Speaking_List')
                            <li>
                                <a class="{{ $isActiveSpeaking ? 'mm-active' : '' }}"
                                    href="{{ route('admin.speak-list') }}">
                                    <i class="metismenu-icon fa-solid fa-laptop-file"></i>
                                    Topics Management
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif
            {{-- Reading --}}
            @if (Gate::check('Reading_List'))
                <li class="mm-parent">
                    <a href="#" aria-expanded="false">
                        <i class="metismenu-icon fa-solid fa-book-open-reader"></i>
                        Reading
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul class="mm-collapse">
                        @php
                            $isActiveReading = request()->is(['admin/reading-level-test/topic-list', 
                            'admin/reading-level-test/topic-create',
                            'admin/reading-level-test/topic-detail/*']);
                        @endphp
                        @can('Reading_List')
                            <li>
                                <a class="{{ $isActiveReading ? 'mm-active' : '' }}"
                                    href="{{ route('admin.reading-topic-list') }}">
                                    <i class="metismenu-icon fa-solid fa-laptop-file"></i>
                                    Topics Management
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif
            {{-- Writing --}}
            @if (Gate::check('Lesson_List'))
                <li class="mm-parent">
                    <a href="#" aria-expanded="false">
                        <i class="metismenu-icon fa fa-pencil" aria-hidden="true"></i>
                        Writing
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul class="mm-collapse">
                        @php
                            $isActiveLesson = request()->is(['admin/lesson', 
                            'admin/lesson/topic-create',
                            'admin/lesson/topic-detail/*']);
                        @endphp
                        @can('Lesson_List')
                            <li>
                                <a class="{{ $isActiveLesson ? 'mm-active' : '' }}"
                                    href="{{ route('admin.lesson-list') }}">
                                    <i class="metismenu-icon fa-solid fa-swatchbook"></i>
                                    Topics Management
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif

            @if (Gate::check('User_List') || Gate::check('Role_List'))
                <li class="app-sidebar__heading">User</li>
            @endif
            @can('User_List')
                @php
                    $isActiveUser = request()->is(['admin/users',
                    'admin/user/edit/*']);
                @endphp
                <li>
                    <a class="{{ $isActiveUser ? 'mm-active' : '' }}"
                        href="{{ route('admin.user-list') }}">
                        <i class="metismenu-icon fa-regular fa-user"></i>
                        {{ Auth::user()->is_admin == 1 ? 'User management' : 'Test History' }}

                    </a>
                </li>
            @endcan
            @can('Role_List')
                @php
                    $isActiveRole = request()->is(['admin/roles',
                    'admin/role/edit/*']);
                @endphp
                <li>
                    <a class="{{ $isActiveRole ? 'mm-active' : '' }}"
                        href="{{ route('admin.role-list') }}">
                        <i class="metismenu-icon fa-solid fa-users"></i>
                        Role management
                    </a>
                </li>
            @endcan
            <li>
                <li>
                    <a
                        href="{{ route('logout') }}">
                        <i class="metismenu-icon fas fa-sign-out-alt"></i>
                        Logout
    
                    </a>
                </li>

        </ul> 
    </div>
</div>

