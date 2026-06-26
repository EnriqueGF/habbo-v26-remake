@extends('layouts.community')

@section('title', 'Forum')
@section('bodyId', 'viewmode')
@php($activeNav = 'community')
@php($noColumn3 = true)

@push('head')
    <link rel="stylesheet" href="/web-gallery/styles/myhabbo/myhabbo.css" type="text/css" />
    <link rel="stylesheet" href="/web-gallery/styles/myhabbo/skins.css" type="text/css" />
    <link rel="stylesheet" href="/web-gallery/styles/myhabbo/dialogs.css" type="text/css" />
    <link rel="stylesheet" href="/web-gallery/styles/myhabbo/buttons.css" type="text/css" />
    <link rel="stylesheet" href="/web-gallery/styles/myhabbo/control.textarea.css" type="text/css" />
    <link rel="stylesheet" href="/web-gallery/styles/myhabbo/boxes.css" type="text/css" />
    <link rel="stylesheet" href="/web-gallery/v2/styles/myhabbo.css" type="text/css" />

    <script src="/web-gallery/static/js/homeview.js" type="text/javascript"></script>
    <script src="/web-gallery/static/js/homeauth.js" type="text/javascript"></script>
    <link rel="stylesheet" href="/web-gallery/v2/styles/group.css" type="text/css" />
    <style type="text/css">

        #playground, #playground-outer {
            width: 752px;
            height: 1360px;
        }

    </style>

    <link href="/web-gallery/styles/discussions.css" type="text/css" rel="stylesheet"/>
@endpush

@section('subnav')
    @include('partials.navi2', ['section' => 'community', 'active' => 'forum'])
@endsection

@section('content')
    <div id="mypage-wrapper" class="cbb blue">
        <div class="box-tabs-container box-tabs-left clearfix">
            <div class="myhabbo-view-tools">
            </div>
            <h2 class="page-owner">
                Forum
            </h2>
            <ul class="box-tabs">
                <li class="selected"><a href="/forum">Forum</a><span class="tab-spacer"></span></li>
            </ul>
        </div>
        <div id="mypage-content">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" class="content-1col">
                <tr>
                    <td valign="top" style="width: 750px;" class="habboPage-col rightmost">
                        <div id="discussionbox">
                            <div id="group-topiclist-container">
                                <div class="topiclist-header clearfix">
                                    <input type="hidden" id="email-verfication-ok" value="1"/>
                                    @if($loggedIn)<a href="#" id="newtopic-upper" class="new-button verify-email newtopic-icon" style="float:left"><b><span></span>Nuevo tema</b><i></i></a>@endif
                                    <div class="page-num-list">
                                        P&aacute;g.
@for($i = 1; $i <= $pages; $i++)
@if($page == $i)
{{ $i }}
@else
<a href="/forum?page={{ $i }}" class="topiclist-page-link">{{ $i }}</a>
@endif
@endfor
                                    </div>
                                </div>
                                <table class="group-topiclist" border="0" cellpadding="0" cellspacing="0" id="group-topiclist-list">
                                    <tr class="topiclist-columncaption">
                                        <td class="topiclist-columncaption-topic">Tema</td>
                                        <td class="topiclist-columncaption-lastpost">&Uacute;ltimo mensaje</td>
                                        <td class="topiclist-columncaption-replies">Respuestas</td>
                                        <td class="topiclist-columncaption-views">Vistas</td>
                                    </tr>

@if($total == 0)
                                    <tr class="topiclist-row-1">
                                        <td class="topiclist-rowtopic" valign="top">
                                            Ning&uacute;n tema.
                                        </td>
                                    </tr>
@endif

@php($key = 0)
@foreach($stickyThreads as $row)
@php($key++)
@php($x = ($key % 2 === 0) ? 'odd' : 'even')
@php($threadPages = (int) ceil(($row->posts + 1) / 10))
@php($dateBits = explode(' ', $row->date))
@php($lastBits = explode(' ', $row->lastpost_date))
                                    <tr class="topiclist-row-{{ $x }}">
                                        <td class="topiclist-rowtopic" valign="top">
                                            <div class="topiclist-row-content">
                                            <a class="topiclist-link icon icon-sticky" href="/viewthread.php?thread={{ $row->id }}">{!! stripslashes(nl2br(htmlspecialchars($row->title))) !!}</a>@if($row->type == 4)&nbsp;<span class="topiclist-row-topicsticky"><img src="/web-gallery/images/groups/status_closed.gif" title="Closed Thread" alt="Closed Thread"></span>@endif&nbsp;(p&aacute;g. @for($i = 1; $i <= $threadPages; $i++)<a href="/viewthread.php?thread={{ $row->id }}&page={{ $i }}" class="topiclist-page-link">{{ $i }}</a>
@endfor)
                                            <br />
                                            <span><a class="topiclist-row-openername" href="/user_profile.php?name={{ $row->author }}">{{ $row->author }}</a></span>&nbsp;<span class="latestpost">{{ $dateBits[0] ?? '' }}</span>
                                            <span class="latestpost">({{ $dateBits[1] ?? '' }})</span>
                                            </div>
                                        </td>
                                        <td class="topiclist-lastpost" valign="top">
                                            <a class="lastpost-page-link" href="/viewthread.php?thread={{ $row->id }}&sp=JumpToLast"><span class="lastpost">{{ $lastBits[0] ?? '' }}</span>
                                            <span class="lastpost">({{ $lastBits[1] ?? '' }})</span></a><br />
                                            <span class="topiclist-row-writtenby">por:</span> <a class="topiclist-row-openername" href="/user_profile.php?name={{ $row->lastpost_author }}">{{ $row->lastpost_author }}</a>&nbsp;
                                        </td>
                                        <td class="topiclist-replies" valign="top">{{ $row->posts }}</td>
                                        <td class="topiclist-views" valign="top">{{ $row->views }}</td>
                                    </tr>
@endforeach

@foreach($normalThreads as $row)
@php($key++)
@php($x = ($key % 2 === 0) ? 'odd' : 'even')
@php($threadPages = (int) ceil(($row->posts + 1) / 10))
@php($dateBits = explode(' ', $row->date))
@php($lastBits = explode(' ', $row->lastpost_date))
                                    <tr class="topiclist-row-{{ $x }}">
                                        <td class="topiclist-rowtopic" valign="top">
                                            <div class="topiclist-row-content">
                                            <a class="topiclist-link " href="/viewthread.php?thread={{ $row->id }}">{!! stripslashes(nl2br(htmlspecialchars($row->title))) !!}</a>@if($row->type == 2)&nbsp;<span class="topiclist-row-topicsticky"><img src="/web-gallery/images/groups/status_closed.gif" title="Closed Thread" alt="Closed Thread"></span>@endif&nbsp;(p&aacute;g. @for($i = 1; $i <= $threadPages; $i++)<a href="/viewthread.php?thread={{ $row->id }}&page={{ $i }}" class="topiclist-page-link">{{ $i }}</a>
@endfor)
                                            <br />
                                            <span><a class="topiclist-row-openername" href="/user_profile.php?name={{ $row->author }}">{{ $row->author }}</a></span>&nbsp;<span class="latestpost">{{ $dateBits[0] ?? '' }}</span>
                                            <span class="latestpost">({{ $dateBits[1] ?? '' }})</span>
                                            </div>
                                        </td>
                                        <td class="topiclist-lastpost" valign="top">
                                            <a class="lastpost-page-link" href="/viewthread.php?thread={{ $row->id }}&sp=JumpToLast"><span class="lastpost">{{ $lastBits[0] ?? '' }}</span>
                                            <span class="lastpost">({{ $lastBits[1] ?? '' }})</span></a><br />
                                            <span class="topiclist-row-writtenby">por:</span> <a class="topiclist-row-openername" href="/user_profile.php?name={{ $row->lastpost_author }}">{{ $row->lastpost_author }}</a>&nbsp;
                                        </td>
                                        <td class="topiclist-replies" valign="top">{{ $row->posts }}</td>
                                        <td class="topiclist-views" valign="top">{{ $row->views }}</td>
                                    </tr>
@endforeach

                                </table>
                                <div class="topiclist-footer clearfix">
                                    @if($loggedIn)<a href="#" id="newtopic-lower" class="new-button verify-email newtopic-icon" style="float:left"><b><span></span>Nuevo tema</b><i></i></a>@else Debes estar conectado para publicar temas.@endif
                                    <div class="page-num-list">
                                        P&aacute;g.
@for($i = 1; $i <= $pages; $i++)
@if($page == $i)
{{ $i }}
@else
<a href="/forum?page={{ $i }}" class="topiclist-page-link">{{ $i }}</a>
@endif
@endfor
                                    </div>
                                </div>
                            </div>

                            <script type="text/javascript" language="JavaScript">
                            L10N.put("myhabbo.discussion.error.topic_name_empty", "El t&iacute;tulo del tema no puede estar vac&iacute;o");
                            Discussions.initialize("0", "forum.php", null);
                            </script>
                        </div>

                    </td>
                    <td style="width: 4px;"></td>
                    <td valign="top" style="width: 164px;">
                        <div class="habblet ">

                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <script type="text/javascript">
        Event.observe(window, "load", observeAnim);
        document.observe("dom:loaded", initDraggableDialogs);
    </script>
@endsection

@push('body_end')
<div class="cbb topdialog black" id="dialog-group-settings">

    <div class="box-tabs-container">
        <ul class="box-tabs">
            <li class="selected" id="group-settings-link-group"><a href="#">Ajustes del grupo</a><span class="tab-spacer"></span></li>
            <li id="group-settings-link-forum"><a href="#">Ajustes del foro</a><span class="tab-spacer"></span></li>
            <li id="group-settings-link-room"><a href="#">Ajustes de sala</a><span class="tab-spacer"></span></li>
        </ul>
    </div>

    <a class="topdialog-exit" href="#" id="dialog-group-settings-exit">X</a>
    <div class="topdialog-body" id="dialog-group-settings-body">
        <p style="text-align:center"><img src="/web-gallery/images/progress_bubbles.gif" alt="" width="29" height="6" /></p>
    </div>
</div>

<script language="JavaScript" type="text/javascript">
Event.observe("dialog-group-settings-exit", "click", function(e) {
    Event.stop(e);
    closeGroupSettings();
}, false);
</script><div class="cbb topdialog" id="postentry-verifyemail-dialog">
    <h2 class="title dialog-handle">Confirmar e-mail</h2>

    <a class="topdialog-exit" href="#" id="postentry-verifyemail-dialog-exit">X</a>
    <div class="topdialog-body" id="postentry-verifyemail-dialog-body">
    <p>Debes confirmar tu e-mail antes de publicar.</p>
    <p><a href="/profile?tab=3">Activa tu e-mail</a></p>
    <p class="clearfix">
        <a href="#" id="postentry-verifyemail-ok" class="new-button"><b>OK</b><i></i></a>
    </p>
    </div>
</div>
@endpush
