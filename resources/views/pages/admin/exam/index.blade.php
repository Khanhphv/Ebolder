@extends('welcome')
@section('content')
    <div class="app-main__inner static lg:relative">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="fa-solid fa-chart-simple icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div>
                        <p>Tests Management</p>
                    </div>

                </div>
            </div>
        </div>
        <div class="tabs-animation">
            <exam-list />
        </div>
    </div>
   
@endsection
@section('modal')
@endsection

@section('js')
   

@endsection

