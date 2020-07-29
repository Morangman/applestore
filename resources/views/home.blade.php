@extends('layouts.app')

@section('content')
    @yield('header', View::make('header', ['categories' => $categories, 'settings' => $settings]))
    <section class="header-baner">
        <div class="header-baner-title">
            <p class="header-baner-title-head hidden-opacity">Оригинальная техника Apple со скидкой до 32%</p>
            <p class="header-baner-title-desc hidden-opacity animate-infScale">Гарантия лучшей цены. Оплата после получения товара. Доставка в день заказа.&nbsp;</p>
            <button class="know-more hidden-opacity">Товары со скидкой</button>
        </div>
        <div class="carousel slide" data-ride="carousel" id="carousel-example-generic">usel-inner">
            <div class="item active">
                <img width="100%" src="{{ asset('client/fon.jpg') }}">
                <div class="carousel-caption">
                    <h3>
                        Оригинальная техника Apple со скидкой до 30%</h3>
                    <p>
                        <button class="know-more hidden-opacity">Купить</button>
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section class="advantages container-fluid" id="press-about">
        <div class="container" id="advantages">
            <div class="row">
                <div class="col-xs-12">
                    <p class="press-about-title hidden-opacity">Преимущества</p>
                </div>
            </div>
            <div class="row advantages-row">
                <div class="col-md-4 col-xs-12 hidden-opacity"><img src="{{ asset('client/themes/site/img/advantages-1.png') }}">
                    <p>Только оригинальная продукция<br>
                        со скидкой до 30%</p>
                </div>
                <div class="col-md-4 col-xs-12 hidden-opacity"><img src="{{ asset('client/themes/site/img/advantages-2.png') }}">
                    <p>Гарантия 1 год</p>
                </div>
                <div class="col-md-4 col-xs-12 hidden-opacity"><img src="{{ asset('client/themes/site/img/advantages-3.png') }}">
                    <p>Тест-драйв 30 дней</p>
                </div>
            </div>
            <div class="row advantages-row">
                <div class="col-md-4  col-xs-12 hidden-opacity"><img src="{{ asset('client/themes/site/img/advantages-4.png') }}">
                    <p>Быстрая доставка</p>
                </div>
                <div class="col-md-4 col-xs-12 hidden-opacity"><img width="100px" src="{{ asset('client/themes/site/img/5i.jpg') }}">
                    <p>5 лет успешной работы</p>
                </div>
                <div class="col-md-4 col-xs-12 hidden-opacity"><img width="100px" src="{{ asset('client/themes/site/img/pe.png') }}">
                    <p>6000 счастливых покупателей</p>
                </div>
            </div>
        </div>
    </section>
    <section class="catalogue container" id="catalogue"></section>


    <products
        :products="{{ json_encode($products) }}"
    ></products>


    <section class="container-fluid delivery" id="akcii">
        <div class="container">
            <div class="row">
                <div id="present" class="module test2 presents-box" style="margin-top:  20px; padding-bottom: 40px;">
                    <div class="container-12 relative">
                        <div id="header3" class="module-header">Защитное стекло и чехол от Apple в подарок!</div>
                        <div style=""></div>
                        <div style="float: left; width: 450px; font-size: 18px; font-weight: 400; padding-top: 40px; padding-left: 40px; visibility: visible; animation-duration: 1s; animation-name: slideInLeft;" class="wow slideInLeft moduleee" data-wow-duration="1s">
                            <div>
                                <div class="point1"></div> Отлично защищает от падений
                            </div>
                            <div style="margin-top: 20px;">
                                <div class="point1"></div> Разные цвета на выбор
                            </div>
                            <div style="margin-top: 20px;">
                                <div class="point1"></div> В наличии чехлы для всех моделей iPhone
                            </div>
                        </div>
                        <div style="float: left; text-align: center; width: 450px; margin-top: 0px; margin-bottom: 0px; visibility: visible; animation-duration: 1s; animation-name: slideInRight;" class="wow slideInRight moduleeeim" data-wow-duration="1s">
                            <img class="primggg" src="{{ asset('client/present.jpg') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div id="warranty" class="module test2 warranty-box" style="margin-top:  20px; background-color: #fafafa; padding-bottom: 0px;">
        <div class="container-12 relative">
            <div id="header3" class="module-header">Гарантия - 1 год</div>
            <div style="float: left; width: 960px; padding-top: 40px;" class="newsec2">
                <div style="float: left; width: 480px; visibility: visible; animation-name: slideInLeft;" class="wow slideInLeft newsec2-1">
                    <div style="margin-top: 20px; margin-left: 10px; font-size: 23px;" id="test2-text2" class="test2-text2">Закон о защите прав потребителей</div>
                    <div id="test2-text4" class="test2-text4" contenteditable="false" style="line-height: 23px; font-size: 16px; margin-left: 10px;">
                        Если за 14 дней Вы обнаружите какую-либо неисправность, мы любезно вернем Вам деньги без скандалов и головной боли. Единственное требование - сохранение товарного вида.
                    </div>
                </div>
                <div style="float: left; width: 480px; visibility: visible; animation-name: slideInRight;" class="wow slideInRight newsec2-1">
                    <div id="test2-text3" style="margin-top: 20px; margin-left: 10px; font-size: 23px;" class="test2-text3">Расширенная гарантия – 1 год</div>
                    <div id="test2-text5" class="test2-text4" contenteditable="false" style="line-height: 23px; font-size: 16px; margin-left: 10px; padding-bottom: 40px">
                        <div>Если в течение 1 года смартфон сломается - специалисты
                            <div> отремонтируют его бесплатно. Вы просто приезжаете</div> с телефоном и гарантийным талоном в сервисный центр.
                        </div>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <section class="container-fluid delivery" id="delivery">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2 col-xs-12">
                    <p class="press-about-title hidden-opacity">Доставка и оплата</p>
                </div>
                <div class="row delivery-icons advantages-row">
                    <div class="col-md-3 col-xs-12 hidden-opacity"><img src="{{ asset('client/themes/site/img/dost.png') }}">
                        <p>Доставка за 2 часа!*
                        </p>
                    </div>
                    <div class="col-md-3 col-xs-12 hidden-opacity"><img src="{{ asset('client/themes/site/img/rub.png') }}">
                        <p>Оплата при получении товара</p>
                    </div>
                    <div class="col-md-3 col-xs-12 hidden-opacity"><img src="{{ asset('client/themes/site/img/kass.png') }}">
                        <p>Кассовый чек**</p>
                    </div>
                    <div class="col-md-3 col-xs-12 hidden-opacity"><img src="{{ asset('client/themes/site/img/garant.png') }}">
                        <p>Гарантийный талон**</p>
                    </div>
                </div>
                <div class="row delivery-desc hidden-opacity">*Доставка осуществляется в течении 2-3х часов с момента оформления заказа.<br>
                    **Предоставляется при покупке.</div>
            </div>
        </div>
    </section>
    <div class="text">
        <section class="container-fluid contacts" id="contacts">
            <address>
                <p class="press-about-title hidden-opacity">Контакты</p>
                <p class="press-about-text-2 hidden-opacity">г. Львов, ул. Генерала Чупринки 10.<br>
                    e-mail: {{ $settings->getAttribute('general_settings')['contact_email'] }} <br>
                    <a href="tel:{{ $settings->getAttribute('general_settings')['phone'] }}">
                        <span class="lptracker_phone">{{ $settings->getAttribute('general_settings')['phone'] }}</span>
                    </a>
                </p>
                <p class="press-about-text-2 pickup">Прием заказов: круглосуточно <br>
                    пн-пт, 10:00 - 20:00 <br>
                    сб-вскр 10:00-18:00 <br>
                </p>
            </address>
            <div class="hidden-opacity" id="map">
                <iframe style="width: 100%; height: 31.25rem;" class="contacts__map-iframe" height="370px" id="myIframe" allowfullscreen="" frameborder="0" src="{{ $settings->getAttribute('general_settings')['iframe_map'] }}"></iframe>
            </div>
            <p style="text-align: center;"><br>© 2012-2020. Все права защищены.</p>
        </section>
    @yield('footer', View::make('footer'))
@endsection
