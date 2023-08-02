<div class="inline-page-menu my-4">
    <ul class="list-unstyled">
        <li class="{{ Request::is('admin/business-settings/sms-module') ?'active':'' }}"><a href="{{route('admin.business-settings.sms-module')}}">{{\App\CPU\translate('SMS_Config')}}</a></li>
{{--        <li class="{{ Request::is('admin/business-settings/mail') ?'active':'' }}"><a href="{{route('admin.business-settings.mail.index')}}">{{\App\CPU\translate('Mail_Config')}}</a></li>--}}
        <li class="{{ Request::is('admin/business-settings/payment-method') ?'active':'' }}"><a href="{{route('admin.business-settings.payment-method.index')}}">{{\App\CPU\translate('Payment_Methods')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/captcha') ?'active':'' }}"><a href="{{route('admin.business-settings.captcha')}}">{{\App\CPU\translate('Recaptcha')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/map-api') ?'active':'' }}"><a href="{{route('admin.business-settings.map-api')}}">{{\App\CPU\translate('Google_Map_APIs')}}</a></li>
{{--        <li class="{{ Request::is('admin/business-settings/fcm-index') ?'active':'' }}"><a href="{{route('admin.business-settings.fcm-index')}}">{{\App\CPU\translate('Push_Notification_Setup')}}</a></li>--}}
{{--        <li class="{{ Request::is('admin/social-login/view') ?'active':'' }}"><a href="{{route('admin.social-login.view')}}">{{\App\CPU\translate('Social_Media_Login')}}</a></li>--}}
        <li class="{{ Request::is('admin/social-media-chat/view') ?'active':'' }}"><a href="{{route('admin.social-media-chat.view')}}">{{\App\CPU\translate('Social_Media_Chat')}}</a></li>
    </ul>
</div>
