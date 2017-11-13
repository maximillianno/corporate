@extends(env('THEME').'.layouts.site')
@section('content')

    <div id="content-home" class="content group">
        <div class="hentry group">
            <form id="contact-form-contact-us" class="contact-form" method="post" action="{{ url('/login') }}" enctype="multipart/form-data">
                {{csrf_field()}}

                <fieldset>
                    <ul>
                        <li class="text-field">
                            <label for="name-contact-us">
                                <span class="label">Имя пользователя</span>

                            </label>
                            <div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span><input type="text" name="login" id="login" class="required" value="" /></div>
                            <div class="msg-error"></div>
                        </li>
                        <li class="text-field">
                            <label for="email-contact-us">
                                <span class="label">Пароль</span>

                            </label>
                            <div class="input-prepend"><span class="add-on"><i class="icon-key"></i></span><input type="text" name="password" id="password" class="required email-validate" value="" /></div>
                            <div class="msg-error"></div>
                        </li>

                        <li class="submit-button">
                            <input type="submit" name="yit_sendmail" value="Send Message" class="sendmail alignright" />
                        </li>
                    </ul>
                </fieldset>
            </form>
            <script type="text/javascript">
                var messages_form_126 = {
                    name: "Please, fill in your name",
                    email: "Please, insert a valid email address",
                    message: "Please, insert your message"
                };
            </script>
        </div>
        <!-- START COMMENTS -->
        <div id="comments">
        </div>
        <!-- END COMMENTS -->
    </div>
@endsection