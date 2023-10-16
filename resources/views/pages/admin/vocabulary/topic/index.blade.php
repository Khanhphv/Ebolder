@extends('welcome')
@section('content')
    <div class="app-main__inner static lg:relative">
        <!---------------------     Start head content  section     --------------------->
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="lnr-book icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div>
                        VOCABULARY LEVEL TEST
                    </div>
                </div>
                <div class="page-title-actions">
                    <span class="btn-icon-wrapper pr-2">
                        <a class="btn-icon btn dev-button btn-primary" href="{{route('admin.vocabulary-question-create')}}" style="padding: 10px 15px;">
                            <i class="pe-7s-plus dev-icon btn-icon-wrapper " style="margin-top: -3px;"></i>Create New Question
                        </a>
                    </span>
                </div>
            </div>
            
        </div>
        <!---------------------     End head content section     --------------------->
        <!---------------------     Start main  section     --------------------->
        <div class="tabs-animation">
            <vocabulary-list />
            <!---------------------     Start content  section     --------------------->

        </div>
        <!---------------------     End main  content  section      --------------------->
    </div>
@endsection
@section('modal')
@endsection

