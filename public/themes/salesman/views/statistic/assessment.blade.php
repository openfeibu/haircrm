<div class="main">
    <div class="main_full fb-clearfix " style="margin-top: 15px;">

        <div class="layui-row">
            <div class="layui-card-box layui-col-space15  fb-clearfix">
                <div class="layui-col-sm12 layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            <b>月KPI考核</b>
                        </div>
                        <form class="layui-form" action="" lay-filter="fb-form" id="new_customer_form">
                            <div class="layui-row">
                                <div class="layui-col-md12 "  style="margin:15px">
                                    <div class="layui-form-item">
                                        <div class="layui-inline">
                                            <label class="layui-form-label">选择月份:</label>
                                            <div class="layui-input-inline" >
                                                <input class="layui-input search_key" name="year_month" id="year_month" autocomplete="off" style="width: 200px;">
                                            </div>
                                        </div>
                                        <div class="layui-inline">
                                            <button class="layui-btn" data-type="reload" type="submit">{{ trans('app.search') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="layui-card-body layuiadmin-card-list">
                            <div>
                                <fieldset class="layui-elem-field layui-field-title" style="margin-top: 50px;">
                                    <legend>{{ $salesman->name }}</legend>
                                </fieldset>
                                <table class="layui-table" id="assessment_{{ $salesman->id }}" lay-filter="assessment_{{ $salesman->id }}" lay-size="sm" >
                                    <thead>
                                    <tr>
                                        <th lay-data="{field:'name',width:'220'}">{{ trans('assessment.label.name')}}</th>
                                        <th lay-data="{field:'description',width:'160'}">{{ trans('assessment.label.description')}}</th>
                                        <th lay-data="{field:'standard',width:'55'}">{{ trans('assessment.label.standard')}}</th>
                                        <th lay-data="{field:'proportion',width:'55'}">{{ trans('assessment.label.proportion')}}</th>
                                        <th lay-data="{field:'lowest_completion_rate',width:'90'}">{{ trans('assessment.label.lowest_completion_rate')}}</th>
                                        <th lay-data="{field:'bonus',width:'80'}">{{ trans('assessment.label.bonus')}}</th>
                                        <th lay-data="{field:'indicators',width:'55'}">{{ trans('assessment.label.indicators')}}</th>
                                        <th lay-data="{field:'progress',width:'80'}">{{ trans('assessment.label.progress')}}</th>
                                        <th lay-data="{field:'completion',width:'75'}">{{ trans('assessment.label.completion')}}</th>
                                        <th lay-data="{field:'score',width:'75'}">{{ trans('assessment.label.score')}}</th>
                                        <th lay-data="{field:'get_bonus',width:'75'}">{{ trans('assessment.label.get_bonus')}}</th>
                                        <th lay-data="{field:'total_bonus',width:'70'}">{{ trans('assessment.label.total_bonus')}}</th>
                                        <th lay-data="{field:'total_score',width:'65'}">{{ trans('assessment.label.total_score')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($assessments as $key => $assessment )
                                    <tr>
                                        <td>{{ $assessment->name }}</td>
                                        <td>{{ $assessment->description }}</td>
                                        <td>{{ $assessment->standard }}</td>
                                        <td>{{ $assessment->proportion }}</td>
                                        <td>@if($assessment->lowest_completion_rate){{ $assessment->lowest_completion_rate }}%@else / @endif</td>
                                        <td>
                                            <!-- 项目奖金 -->
                                            @if($assessment->type == 'performance')
                                                <?php $bonus = $assessment['proportion'] / 100 * $performance_bonus; ?>
                                                {!! $bonus !!}
                                            @else
                                                /
                                            @endif
                                        </td>
                                        <td>{!! ceil($assessment->lowest_completion_rate / 100 *  $assessment->standard) !!}</td>
                                        <td>
                                            <!-- 完成情况 -->
                                            @if($assessment->slug && isset($salesman->assessment[$assessment->slug]))
                                                {!! $salesman->assessment[$assessment->slug] !!}
                                            @else
                                                /
                                            @endif
                                        </td>
                                        <td>
                                            <!-- 完成率 -->
                                            @if($assessment->slug && isset($salesman->assessment[$assessment->slug]))
                                                <?php $completion_rate = sprintf("%01.2f", $salesman->assessment[$assessment->slug]/$assessment->standard*100); ?>
                                                {!! $completion_rate.'%' !!}
                                            @else
                                                /
                                            @endif
                                        </td>
                                        <td>
                                            <!-- 得分 -->
                                            @if($assessment->type == 'performance')
                                                <?php $score = round(($completion_rate > 100 ? 100 :  ($completion_rate / 100 * $bonus))/$performance_bonus * 100,2); ?>
                                                {!! $score !!}
                                            @else
                                                /
                                            @endif
                                        </td>
                                        <td>
                                            <!-- 获得奖金 -->
                                            <?php
                                                $get_bonus = 0;
                                                if($assessment->type == 'performance'){
                                                    if($completion_rate >= $assessment->lowest_completion_rate){
                                                        if($completion_rate >= 100){
                                                            $get_bonus = $bonus;
                                                        }else{
                                                            $get_bonus = round($bonus * $completion_rate/100,2);
                                                        }

                                                    }
                                                }
                                            ?>
                                            @if($assessment->type == 'performance')
                                                @if($completion_rate >= $assessment->lowest_completion_rate)
                                                    @if($completion_rate >= 100)
                                                        {{ $bonus }}
                                                    @else
                                                        {{ round($bonus * $completion_rate/100,2) }}
                                                    @endif
                                                @else
                                                    0
                                                @endif
                                            @else
                                                /
                                            @endif
                                        </td>
                                        <td>{{ $salesman->total_bonus }}</td>
                                        <td>{{ $salesman->total_score }}</td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    layui.use(['jquery','element','form','table','laydate','echarts'], function(){
        var form = layui.form;
        var element = layui.element;
        var $ = layui.$;
        var laydate = layui.laydate;
        var table = layui.table;
        laydate.render({
            elem: '#year_month'
            ,type: 'month'
            ,value: '{!! $year_month !!}'
        });

        table.init('assessment_{{ $salesman->id }}',{width:'1168',done:function(res,curr,count){
            $.layuiRowspan('assessment_{{ $salesman->id }}','total_bonus',1);
            $.layuiRowspan('assessment_{{ $salesman->id }}','total_score',1);
        }
        });

    });
</script>