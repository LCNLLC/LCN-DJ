@extends('backend.layouts.app')

@section('content')
    <style>
        .lead {
            font-size: 1.09375rem;
            font-weight: 300;
        }
        .tile {
            position: relative;
            background: #ffffff;
            border-radius: 3px;
            padding: 20px;
            -webkit-box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14),
                0 1px 5px 0 rgba(0, 0, 0, 0.12),
                0 3px 1px -2px rgba(0, 0, 0, 0.2);
            box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14),
                0 1px 5px 0 rgba(0, 0, 0, 0.12),
                0 3px 1px -2px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
            -webkit-transition: all 0.3s ease-in-out;
            -o-transition: all 0.3s ease-in-out;
            transition: all 0.3s ease-in-out;
        }
    </style>

    <div class="container-fluid">
        <!-- Dashboard Analytics -->
        <div class="row">
            <div class="col-md-12">
                <h2 class="mb-4">{{ translate('Referral Dashboard') }}</h2>
                <div class="row">
                    <div class="col-md-3">
                        <div class="tile text-center">
                            <h4>{{ translate('Total Referrals') }}</h4>
                            <h2>{{ $analytics['total_referrals'] }}</h2>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="tile text-center">
                            <h4>{{ translate('Active Referrals') }}</h4>
                            <h2>{{ $analytics['total_active'] }}</h2>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="tile text-center">
                            <h4>{{ translate('Average Commission (%)') }}</h4>
                            <h2>{{ number_format($analytics['avg_percent'], 2) }}</h2>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="tile text-center">
                            <h4>{{ translate('Total People') }}</h4>
                            <h2>{{ $analytics['total_people'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Dashboard Analytics -->

        <div class="row">
            <div class="col-md-12">
                @if (count($errors) > 0)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                    ×
                                </button>
                                <strong class="col-md-12">
                                    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                    {{ translate('Alert!') }}
                                </strong>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row">
                    <!-- Current Settings Table -->
                    <div class="col-md-6">
                        <div class="tile">
                            <div class="table-responsive">
                                <strong class="lead">{{ translate('CURRENT SETTING') }}</strong>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>{{ translate('Level') }}</th>
                                            <th>{{ translate('Commission') }}</th>
                                            <th>{{ translate('People') }}</th>
                                            <th>{{ translate('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($trans as $p)
                                            <tr>
                                                <td>{{ translate('Level') }} # {{ $p->level }}</td>
                                                <td>{{ $p->percent }} %</td>
                                                <td>{{ $p->people }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                                        data-target="#updateModal{{ $p->id }}">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                    <a href="{{ route('refer.delete', $p->id) }}" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Are you sure you want to delete this?');">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>

                                            <!-- Update Modal -->
                                            <div class="modal fade" id="updateModal{{ $p->id }}" tabindex="-1" role="dialog"
                                                aria-labelledby="updateModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <form action="{{ route('refer.update', $p->id) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="updateModalLabel">
                                                                    {{ translate('Update Level') }}
                                                                </h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">×</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label>{{ translate('Level') }}</label>
                                                                    <input type="number" name="level" class="form-control"
                                                                        value="{{ $p->level }}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>{{ translate('Commission') }}</label>
                                                                    <input type="number" name="percent" class="form-control"
                                                                        value="{{ $p->percent }}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>{{ translate('People') }}</label>
                                                                    <input type="number" name="people" class="form-control"
                                                                        value="{{ $p->people }}" required>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">{{ translate('Close') }}</button>
                                                                <button type="submit" class="btn btn-primary">
                                                                    {{ translate('Update') }}
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Change Settings & Generate Form -->
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="tile">
                                    <strong class="lead">{{ translate('CHANGE SETTING') }}</strong>
                                    <br>
                                    <br>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="number" name="level" id="levelGenerate"
                                                placeholder="{{ translate('HOW MANY LEVEL') }}" class="form-control input-lg">
                                        </div>
                                        <div class="col-md-6">
                                            <button type="button" id="generate"
                                                class="btn btn-success btn-block btn-md">{{ translate('GENERATE') }}</button>
                                        </div>
                                    </div>
                                    <br>
                                    <form action="{{ route('store.refer') }}" id="prantoForm" style="display: none" method="post">
                                        {{ csrf_field() }}
                                        <div class="form-group">
                                            <label class="text-success">
                                                {{ translate('Level & Commission') }} :
                                                <small>({{ translate('Old Levels will Remove After Generate') }})</small>
                                            </label>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="description" style="width: 100%; border: 1px solid #ddd; padding: 10px; border-radius: 5px">
                                                        <div class="row">
                                                            <div class="col-md-12" id="planDescriptionContainer">
                                                                <!-- Generated inputs will appear here -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <button type="submit" class="btn btn-success btn-md">{{ translate('Submit') }}</button>
                                    </form>
                                </div>
                            </div>

                            <!-- Referral Withdraw Rules -->
                            <div class="col-md-12">
                                <div class="tile">
                                    <strong class="lead">{{ translate('REFERRAL WITHDRAW RULES') }}</strong>
                                    <br>
                                    <br>
                                    <div class="tile-body">
                                        <form role="form" method="POST" action="{{ route('general.store') }}">
                                            {{ csrf_field() }}
                                            <div class="form-body mb-4">
                                                <div class="form-row">
                                                    <div class="col-md-6 mb-4">
                                                        <label>
                                                            <strong>{{ translate('Minimum Day to Withdraw') }}</strong>
                                                        </label>
                                                        <div class="input-group">
                                                            <input type="number" name="affiliate_withdraw_day" class="form-control"
                                                                value="{{ referlSetting('affiliate_withdraw_day') }}">
                                                            <div class="input-group-append">
                                                                <div class="input-group-text">{{ translate('Days') }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-4">
                                                        <label>
                                                            <strong>{{ translate('Minimum People for Payment') }}</strong>
                                                        </label>
                                                        <div class="input-group">
                                                            <input type="number" name="affiliate_withdraw_person" class="form-control"
                                                                value="{{ referlSetting('affiliate_withdraw_person') }}">
                                                            <div class="input-group-append">
                                                                <div class="input-group-text">{{ translate('Person') }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-4">
                                                        <label>
                                                            <strong>{{ translate('Payment Release Deadline') }}</strong>
                                                        </label>
                                                        <div class="input-group">
                                                            <input type="number" name="affiliate_withdraw_deadline" class="form-control"
                                                                value="{{ referlSetting('affiliate_withdraw_deadline') }}">
                                                            <div class="input-group-append">
                                                                <div class="input-group-text">{{ translate('Days') }}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <button type="submit" class="btn btn-success btn-block btn-lg">
                                                    {{ translate('Update') }}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- End Referral Withdraw Rules -->
                        </div>
                    </div>
                    <!-- End Change Settings -->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
        integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN"
        crossorigin="anonymous">

    <script>
        $(document).ready(function () {
            $("#generate").on('click', function () {
                var da = $('#levelGenerate').val();
                var a = 0;
                var val = 1;
                var guu = '';
                if (da !== '' && da > 0) {
                    $('#prantoForm').css('display', 'block');
                    for (a; a < parseInt(da); a++) {
                        guu += '<div class="input-group" style="margin-top: 5px">\n' +
                            '<input name="level[]" class="form-control margin-top-10" type="number" readonly value="' + (val++) + '" required placeholder="{{ translate('Level') }}">\n' +
                            '<input name="percent[]" class="form-control margin-top-10" type="number" required placeholder="{{ translate('Commission %') }}">\n' +
                            '<input name="people[]" class="form-control margin-top-10" type="number" required placeholder="{{ translate('People') }}">\n' +
                            '<span class="input-group-btn">\n' +
                            '<button class="btn btn-danger margin-top-10 delete_desc" type="button"><i class="fa fa-times"></i></button></span>\n' +
                            '</div>';
                    }
                    $('#planDescriptionContainer').html(guu);
                } else {
                    alert('{{ translate('Level Field Is Required') }}');
                }
            });

            $(document).on('click', '.delete_desc', function () {
                $(this).closest('.input-group').remove();
            });
        });
    </script>
@stop
