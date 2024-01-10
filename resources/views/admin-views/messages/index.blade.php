@extends('layouts.admin.app')

@section('title', \App\CentralLogics\translate('Messages'))

@push('css_or_js')
    <link rel="stylesheet" href="{{asset('/public/assets/admin/css/lightbox.min.css')}}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')

    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{asset('public/assets/admin/img/icons/message.png')}}" alt="">
                {{\App\CentralLogics\translate('conversation_list')}}
            </h2>
        </div>

        <div class="row gy-4">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body px-2" id="conversation_sidebar">
                        <div class="chat_people media gap-3 mb-4 px-2">
                            <div class="avatar rounded-circle position-relative">
                                <img class="img-fit rounded-circle" src="{{asset('storage/app/public/admin')}}/{{auth('admin')->user()->image}}"
                                     onerror="this.src='{{asset('public/assets/admin')}}/img/160x160/img1.jpg'"
                                     alt="Image Description">
                                <span class="avatar-status status-sm bg-success"></span>
                            </div>
                            <div class="chat_ib media-body">
                                <h5 class="mb-0">{{auth('admin')->user()->f_name}} {{auth('admin')->user()->l_name}}</h5>
                                <span class="fs-12">{{auth('admin')->user()->phone}}</span>
                            </div>
                        </div>

                        <div class="input-group mb-3 px-2">
                            <div class="input-group-prepend">
                              <span class="input-group-text border-right-0 pr-0" id="basic-addon1"><i class="tio tio-search"></i></span>
                            </div>
                            <input type="text" class="cz-filter-search form-control border-left-0 pl-2 focus-none" placeholder="{{ translate('Search_user') }}" aria-label="Username" aria-describedby="basic-addon1" id="search-conversation-user" autocomplete="off">
                        </div>

                        <div class="customer-list-wrap">
                            @php($array=[])
                            @foreach($conversations as $conv)
                                @if(in_array($conv->user_id,$array)==false)
                                    @php(array_push($array,$conv->user_id))
                                    @php($user=\App\User::find($conv->user_id))
                                    @php($unchecked=\App\Model\Conversation::where(['user_id'=>$conv->user_id,'checked'=>0])->count())

                                    @if(isset($user))
                                        <div
                                            class="sidebar_primary_div media gap-3 p-2 mb-2 align-items-center customer-list cursor-pointer rounded {{$unchecked!=0?'conv-active':''}}"
                                            onclick="viewConvs('{{route('admin.message.view',[$conv->user_id])}}','customer-{{$conv->user_id}}')"
                                            id="customer-{{$conv->user_id}}">
                                            <div class="avatar rounded-circle">
                                                <img class="img-fit rounded-circle"
                                                    src="{{asset('storage/app/public/profile/'.$user['image'])}}"
                                                    onerror="this.src='{{asset('public/assets/admin')}}/img/160x160/img1.jpg'"
                                                    alt="Image Description">
                                            </div>
                                            <h5 class="sidebar_name mb-0 d-flex gap-2 justify-content-between align-items-center flex-grow-1">
                                                <div>
                                                    <div>{{$user['f_name'].' '.$user['l_name']}}</div>
                                                    <a class="text-dark fs-12 font-weight-normal" href="tel:{{ $user['phone'] }}">{{ $user['phone'] }}</a>
                                                </div>
                                                <span class="{{$unchecked!=0?'badge badge-soft-info badge-pill':''}}" id="counter-{{$conv->user_id}}">{{$unchecked!=0?$unchecked:''}}</span>
                                            </h5>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8" id="view-conversation">
                <center class="d-flex justify-content-center align-items-center h-100">
                    <h4 class="text-muted">{{\App\CentralLogics\translate('view Conversation')}}</h4>
                </center>
                {{--view here--}}
            </div>
        </div>
        <!-- End Row -->
    </div>

@endsection

@push('script_2')
    {{-- Search --}}
    <script>
        $("#search-conversation-user").on("keyup", function () {
            var input_value = this.value.toLowerCase().trim();

            let sidebar_primary_div = $(".sidebar_primary_div");
            let sidebar_name = $(".sidebar_name");

            for (i = 0; i < sidebar_primary_div.length; i++) {
                const text_value = sidebar_name[i].innerText;
                if (text_value.toLowerCase().indexOf(input_value) > -1) {
                    sidebar_primary_div[i].style.display = "";
                } else {
                    sidebar_primary_div[i].style.setProperty("display", "none", "important");
                }
            }
        });
    </script>

    <script>
        let current_selected_user = null;

        function viewConvs(url, id_to_active) {
            current_selected_user = id_to_active;     //for reloading conversation body

            //inactive selected user from sidebar
            var counter_element = $('#counter-'+ current_selected_user.slice(9));
            var customer_element = $('#'+current_selected_user);
            if(counter_element !== "undefined") {
                counter_element.empty();
                counter_element.removeClass("badge");
                counter_element.removeClass("badge-info");
            }
            if(customer_element !== "undefined") {
                customer_element.removeClass("conv-active");
            }


            $('.customer-list').removeClass('conv-active');
            $('#' + id_to_active).addClass('conv-active');
            $.get({
                url: url,
                success: function (data) {
                    $('#view-conversation').html(data.view);
                }
            });
        }

        function replyConvs(url) {
            var form = document.querySelector('form');
            var formdata = new FormData(form);

            if (!formdata.get('reply') && !formdata.get('images[]')) {
                toastr.error('{{\App\CentralLogics\translate("Reply message is required!")}}', {
                    CloseButton: true,
                    ProgressBar: true
                });
                return "false";
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: url,
                type: 'POST',
                data: formdata,
                processData: false,
                contentType: false,
                success: function (data) {
                    toastr.success('{{translate("Message sent")}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    $('#view-conversation').html(data.view);
                },
                error() {
                    toastr.error('{{\App\CentralLogics\translate("Reply message is required!")}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        }

        function renderUserList() {
            $('#loading').show();
            $.ajax({
                url: "{{route('admin.message.get_conversations')}}",
                type: 'GET',
                cache: false,
                success: function (response) {
                    $('#loading').hide();
                    $("#conversation_sidebar").html(response.conversation_sidebar)

                },
                error: function (err) {
                    $('#loading').hide();
                }
            });
        }

    </script>

    {{-- fcm listener --}}
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-messaging.js"></script>
    <script>
        @php($config=\App\CentralLogics\Helpers::get_business_settings('firebase_message_config'))
        firebase.initializeApp({
            apiKey: "{{ $config['apiKey'] ?? '' }}",
            authDomain: "{{ $config['authDomain'] ?? '' }}",
            projectId: "{{ $config['projectId'] ?? '' }}",
            storageBucket: "{{ $config['storageBucket'] ?? '' }}",
            messagingSenderId: "{{ $config['messagingSenderId'] ?? '' }}",
            appId: "{{ $config['appId'] ?? '' }}"
        });

        const messaging = firebase.messaging();

        //service worker registration
        if ('serviceWorker' in navigator) {
            var swRegistration = navigator.serviceWorker.register('{{ asset('firebase-messaging-sw.js') }}')
                .then(function (registration) {
                    getToken(registration);
                    {{-- toastr.success('{{\App\CentralLogics\translate("Service Worker successfully registered.")}}');--}}
                    //console.log('Registration successful, scope is:', registration.scope);
                    console.log('Service worker registration successful.');
                }).catch(function (err) {
                    {{-- toastr.error('{{\App\CentralLogics\translate("Service Worker Registration failed.")}}');--}}
                    //console.log('Service worker registration failed, error:', err);
                    console.log('Service worker registration failed.');
                });
        }

        function getToken(registration) {
            messaging.requestPermission()
                .then(function () {
                    let token = messaging.getToken({serviceWorkerRegistration: registration});
                    return token;
                })
                .then(function (token) {
                    update_fcm_token(token);    //update admin's fcm token
                })
                .catch((err) => {
                    //console.log('error:: ' + err);
                });
        }



        //Foreground State
        messaging.onMessage(payload => {
            renderUserList();
            if (current_selected_user != null && current_selected_user.slice(9) === payload.notification.body) {
                document.getElementById(current_selected_user).onclick();
            } else {
                toastr.info(payload.notification.title ? payload.notification.title : 'New message arrived.');
            }

        });

        //Background State
        // messaging.setBackgroundMessageHandler(function (payload) {
        //     return self.registration.showNotification(payload.data.title, {
        //         body: payload.data.body ? payload.data.body : '',
        //         icon: payload.data.icon ? payload.data.icon : ''
        //     });
        // });

        function update_fcm_token(token) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: "{{route('admin.message.update_fcm_token')}}",
                data: {
                    fcm_token: token,
                },
                cache: false,
                success: function (data) {
                    // console.log(JSON.stringify(data));
                    // toastr.success(data.message);
                    console.log(data.message);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    toastr.error('{{\App\CentralLogics\translate("FCM token updated failed")}}');
                }
            });
        }

    </script>


@endpush
