<aside class="sidebar-wrapper">

    <span class="sidebar-hide-arrow"><i class="fas fa-arrow-circle-left"></i></span>

    <div class="sidebar-top">

        <div class="profile-area">
            <div class="user-avatar">
                <img class="round-image" src="{{Auth::user()->phurl ? url(Auth::user()->phurl) : url('/images/abstrct_doc.png')}}">
            </div>
            <div class="username-area">
<!--                <p class="user-welcome">Welcome</p>-->
                @auth
                    <p class="user-name">{{request()->user()->fullname}}</p>
                @endauth

            </div>
        </div>
    </div>

    @switch(request()->user()->role)
        @case('Admin')
            @include('shared.adminsidebar')
        @break
        @case('Doctor')
            @include('shared.docsidebar')
        @break
        @case('Reception')
            @include('shared.recepsidebar')
        @break
        @case('Account')
            @include('shared.accsidebar')
        @break
        @case('Nurse')

        @break
        @case('Lab')

        @break
        @case('Staff')

        @break
    @endswitch


</aside>
