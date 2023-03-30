<div class="modal fade" id="{{$id}}" tabindex="-1" role="dialog" aria-labelledby="{{$label}}">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    <span class="badge badge-danger" v-if="entry.is_remote">{{ entry.remote_type }}</span>
                    @{{entry.title}} by @{{entry.author}}
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <dl class="row">
                            <dt class="col-sm-4">
                                ID
                            </dt>
                            <dd class="col-sm-8">
                                @{{ entry.id }}
                            </dd>

                            <dt class="col-sm-4">
                                {{trans('partymeister-competitions::backend/competitions.competition')}}
                            </dt>
                            <dd class="col-sm-8">
                                @{{ entry.competition.data.name }}
                            </dd>

                            <template v-if="entry.competition.data.competition_type.data.has_running_time">
                                <dt class="col-sm-4">
                                    {{trans('partymeister-competitions::backend/entries.running_time')}}
                                </dt>
                                <dd class="col-sm-8">
                                    @{{ entry.running_time }}
                                </dd>
                            </template>

                            <dt class="col-sm-4">
                                {{trans('partymeister-competitions::backend/entries.title')}}
                            </dt>
                            <dd class="col-sm-8">
                                @{{ entry.title }}
                            </dd>

                            <dt class="col-sm-4">
                                {{trans('partymeister-competitions::backend/entries.author')}}
                            </dt>
                            <dd class="col-sm-8">
                                @{{ entry.author }}
                            </dd>

                            <dt class="col-sm-4">
                                {{trans('partymeister-competitions::backend/entries.description')}}
                            </dt>
                            <dd class="col-sm-8">
                                <p v-html="nl2br(entry.description)"></p>
                            </dd>

                            <dt class="col-sm-4">
                                {{trans('partymeister-competitions::backend/entries.organizer_description')}}
                            </dt>
                            <dd class="col-sm-8">
                                <p v-html="nl2br(entry.organizer_description)"></p>
                            </dd>
                        </dl>
                    </div>
                    <div class="col-sm-6">
                        <dl class="row">
                            <template v-if="entry.options">
                                <dt class="col-sm-4">
                                    {{trans('partymeister-competitions::backend/entries.option_info')}}
                                </dt>
                                <dd class="col-sm-8">
                                    <ul class="list-unstyled">
                                        <li v-for="option in entry.options.data">@{{ option.name }}</li>
                                    </ul>
                                </dd>
                                <dt class="col-sm-4">
                                    {{trans('partymeister-competitions::backend/entries.custom_option_short')}}
                                </dt>
                                <dd class="col-sm-8" v-if="entry.custom_option != ''">
                                    @{{ entry.custom_option }}
                                </dd>
                            </template>
                            <template v-if="entry.competition.data.competition_type.data.has_filesize">
                                <dt class="col-sm-4">
                                    {{trans('partymeister-competitions::backend/entries.filesize')}}
                                </dt>
                                <dd class="col-sm-8">
                                    @{{ entry.filesize_human }} (@{{ entry.filesize_bytes }} bytes)
                                </dd>
                            </template>

                            <template v-if="entry.competition.data.competition_type.data.has_platform">
                                <dt class="col-sm-4">
                                    {{trans('partymeister-competitions::backend/entries.platform')}}
                                </dt>
                                <dd class="col-sm-8">
                                    @{{ entry.platform }}
                                </dd>
                            </template>
                            <template v-if="entry.files.data">
                                <dt class="col-sm-4">
                                    {{trans('partymeister-competitions::backend/entries.file_info')}}
                                </dt>
                                <dd class="col-sm-8">
                                    <ul class="list-unstyled">
                                    <li v-for="(file, index) in entry.files.data" style="margin-bottom: 5px;">
                                        {{trans('motor-backend::backend/global.uploaded')}} @{{ file.created_at }}<br>
                                        <a :href="file.url">@{{ file.file_name }}</a>
                                    </li>
                                    </ul>
                                </dd>
                            </template>

                        </dl>
                    </div>
                </div>

                <div class="row clearfix">
                    <template v-if="entry.screenshot">
                        <div class="col-md-6">
                            <h4 style="margin-top: 0.5rem;">{{trans('partymeister-competitions::backend/entries.screenshot')}}</h4>
                            <a data-caption="{{trans('partymeister-competitions::backend/entries.screenshot')}}"
                               data-fancybox="gallery" :href="entry.screenshot.data.url"><img class="img-thumbnail"
                                                                                              :src="entry.screenshot.data.url"/></a>
                        </div>
                    </template>
                    <template v-if="entry.work_stages">
                        <div class="col-md-6">
                            <h4 style="margin-top: 0.5rem;">{{trans('partymeister-competitions::backend/entries.work_stages')}}</h4>
                            <template v-for="(work_stage, index) in entry.work_stages.data">
                                <a data-caption="{{trans('partymeister-competitions::backend/entries.work_stage')}}"
                                   data-fancybox="gallery" :href="work_stage.url"><img style="width: 50%;" class="img-thumbnail"
                                                                                       :src="work_stage.url"/></a>
                            </template>
                        </div>
                    </template>
                </div>
                <template v-if="entry.video || entry.audio">
                    <div class="row">
                        <div class="col-md-6" v-if="entry.video">
                            <h4 style="margin-top: 0.5rem;">{{trans('partymeister-competitions::backend/entries.video')}}</h4>
                            <video class="mejs__player" style="width:100%;height:240px;" controls="controls" width="100%" height="240"
                            :src="entry.video.data.url" data-mejsoptions='{"pluginPath": "/path/to/shims/", "alwaysShowControls": "true"}'></video>
                        </div>
                        <div class="col-md-6" v-if="entry.audio">
                            <h4 style="margin-top: 0.5rem;">{{trans('partymeister-competitions::backend/entries.audio')}}</h4>
                            <audio class="mejs__player" controls width="100%">
                                <source :src="entry.audio.data.url" type="audio/mp3">
                            </audio>
                        </div>
                    </div>
                </template>

                <div class="row">
                    <div class="col-md-6">
                        <h4 style="margin-top: 0.5rem;">{{trans('partymeister-competitions::backend/entries.author_info')}}</h4>
                        <dl class="row">
                            <dt class="col-sm-4">
                                {{trans('partymeister-competitions::backend/entries.name')}}
                            </dt>
                            <dd class="col-sm-8">
                                @{{ entry.author_name }}
                            </dd>

                            <dt class="col-sm-4">
                                {{trans('partymeister-competitions::backend/entries.email')}}
                            </dt>
                            <dd class="col-sm-8">
                                @{{ entry.author_email }}
                            </dd>

                            <dt class="col-sm-4">
                                {{trans('partymeister-competitions::backend/entries.phone')}}
                            </dt>
                            <dd class="col-sm-8">
                                @{{ entry.author_phone }}
                            </dd>

                            <dt class="col-sm-4">
                                {{trans('partymeister-competitions::backend/entries.address')}}
                            </dt>
                            <dd class="col-sm-8">
                                @{{ entry.author_address }} @{{ entry.author_zip }} @{{ entry.author_city }} @{{
                                entry.author_country }}
                            </dd>
                        </dl>
                    </div>
                    <template v-if="entry.competition.data.competition_type.data.has_composer">
                        <div class="col-md-6">
                            <h4 style="margin-top: 0.5rem;">{{trans('partymeister-competitions::backend/entries.composer_info')}}</h4>
                            <dl class="row">
                                <dt class="col-sm-4">
                                    {{trans('partymeister-competitions::backend/entries.name')}}
                                </dt>
                                <dd class="col-sm-8">
                                    @{{ entry.composer_name }}
                                </dd>

                                <dt class="col-sm-4">
                                    {{trans('partymeister-competitions::backend/entries.email')}}
                                </dt>
                                <dd class="col-sm-8">
                                    @{{ entry.composer_email }}
                                </dd>

                                <dt class="col-sm-4">
                                    {{trans('partymeister-competitions::backend/entries.phone')}}
                                </dt>
                                <dd class="col-sm-8">
                                    @{{ entry.composer_phone }}
                                </dd>

                                <dt class="col-sm-4">
                                    {{trans('partymeister-competitions::backend/entries.address')}}
                                </dt>
                                <dd class="col-sm-8">
                                    @{{ entry.composer_address }} @{{ entry.composer_zip }} @{{ entry.composer_city }}
                                    @{{ entry.composer_country }}
                                </dd>

                                <dt class="col-sm-4">
                                    {{trans('partymeister-competitions::backend/entries.composer_gema_cleared')}}
                                </dt>

                                <dd class="col-sm-8">
                                    @{{ bool(entry.composer_not_member_of_copyright_collective) }}
                                </dd>
                            </dl>
                        </div>
                    </template>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
