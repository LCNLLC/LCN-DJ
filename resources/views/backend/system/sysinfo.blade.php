@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="tile">
                <h3 class="tile-title ">{{ translate('System Packages Information') }}</h3>
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th> # </th>
                                <th> {{ translate('Package Name') }} </th>
                                <th> {{ translate('Version') }} </th>
                                <th> {{ translate('Description') }} </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($packages_json as $key => $package)
                                <tr>
                                    <td>{{$key}}</td>
                                    <td>{{$package['name']}}</td>
                                    <td>{{$package['version']}}</td>
                                    <td>{{isset($package['description']) ? $package['description']: ''}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @php
            $server_info = json_decode(file_get_contents(base_path().'/composer.json'), true);

            $mysql = \DB::select('select version()')[0]->{'version()'};

        @endphp
        <div class="col-md-4">
            <div class="tile">
                <h3 class="tile-title ">{{ translate('System Information') }}</h3>
                <div class="tile-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th> # </th>
                                <th> {{ translate('Name') }} </th>
                                <th> {{ translate('Required') }} </th>
                                <th> {{ translate('Installed') }} </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>1</td>
                                <td>PHP & Javascript</td>
                                <td>{{ $server_info['require']['php'] }} </td>
                                <td>{{ phpversion() }}</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Custom FrameWork</td>
                                <td>{{ $server_info['require']['laravel/framework'] }}</td>
                                <td>{{ app()->version() }}</td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>MySql innodb_version</td>
                                <td>{{ $mysql}}</td>
                                <td>{{ $mysql}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

