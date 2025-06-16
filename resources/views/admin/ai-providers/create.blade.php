@extends('layouts.admin')

@section('title', 'Add AI Provider')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Add AI Provider</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dasbor') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.ai-providers.index') }}">AI Providers</a></li>
                        <li class="breadcrumb-item active">Add</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <!-- Alerts -->
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-plus mr-2"></i>
                                Add New AI Provider
                            </h3>
                        </div>
                        <form action="{{ route('admin.ai-providers.store') }}" method="POST">
                            @csrf
                            
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Provider Name</label>
                                            <select class="form-control @error('name') is-invalid @enderror" 
                                                    id="name" 
                                                    name="name" 
                                                    required>
                                                <option value="">Select Provider</option>
                                                <option value="claude" {{ old('name') == 'claude' ? 'selected' : '' }}>Claude (Anthropic)</option>
                                                <option value="openai" {{ old('name') == 'openai' ? 'selected' : '' }}>OpenAI (ChatGPT)</option>
                                                <option value="gemini" {{ old('name') == 'gemini' ? 'selected' : '' }}>Gemini (Google)</option>
                                            </select>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="display_name">Display Name</label>
                                            <input type="text" 
                                                   class="form-control @error('display_name') is-invalid @enderror" 
                                                   id="display_name" 
                                                   name="display_name" 
                                                   value="{{ old('display_name') }}" 
                                                   required>
                                            @error('display_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="api_key">API Key</label>
                                    <div class="input-group">
                                        <input type="password" 
                                               class="form-control @error('api_key') is-invalid @enderror" 
                                               id="api_key" 
                                               name="api_key" 
                                               value="{{ old('api_key') }}" 
                                               required>
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary" id="toggle-api-key">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @error('api_key')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="endpoint">API Endpoint</label>
                                            <input type="url" 
                                                   class="form-control @error('endpoint') is-invalid @enderror" 
                                                   id="endpoint" 
                                                   name="endpoint" 
                                                   value="{{ old('endpoint') }}" 
                                                   required>
                                            @error('endpoint')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="model">Model</label>
                                            <input type="text" 
                                                   class="form-control @error('model') is-invalid @enderror" 
                                                   id="model" 
                                                   name="model" 
                                                   value="{{ old('model') }}" 
                                                   required>
                                            @error('model')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="daily_limit">Daily Limit</label>
                                            <input type="number" 
                                                   class="form-control @error('daily_limit') is-invalid @enderror" 
                                                   id="daily_limit" 
                                                   name="daily_limit" 
                                                   value="{{ old('daily_limit', 1000) }}" 
                                                   min="1" 
                                                   required>
                                            @error('daily_limit')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="monthly_limit">Monthly Limit</label>
                                            <input type="number" 
                                                   class="form-control @error('monthly_limit') is-invalid @enderror" 
                                                   id="monthly_limit" 
                                                   name="monthly_limit" 
                                                   value="{{ old('monthly_limit', 30000) }}" 
                                                   min="1" 
                                                   required>
                                            @error('monthly_limit')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="priority">Priority</label>
                                            <select class="form-control @error('priority') is-invalid @enderror" 
                                                    id="priority" 
                                                    name="priority" 
                                                    required>
                                                @for($i = 1; $i <= 10; $i++)
                                                    <option value="{{ $i }}" {{ old('priority', 1) == $i ? 'selected' : '' }}>
                                                        {{ $i }} {{ $i == 1 ? '(Highest)' : ($i == 10 ? '(Lowest)' : '') }}
                                                    </option>
                                                @endfor
                                            </select>
                                            @error('priority')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" 
                                               class="custom-control-input" 
                                               id="is_active" 
                                               name="is_active" 
                                               value="1"
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">
                                            Active Provider
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-2"></i>Add Provider
                                </button>
                                <a href="{{ route('admin.ai-providers.index') }}" class="btn btn-secondary ml-2">
                                    <i class="fas fa-arrow-left mr-2"></i>Back
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle mr-2"></i>
                                Provider Templates
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="provider-template" data-provider="claude">
                                <h6><i class="fas fa-brain text-purple mr-2"></i>Claude (Anthropic)</h6>
                                <small class="text-muted">
                                    <strong>Endpoint:</strong> https://api.anthropic.com/v1/messages<br>
                                    <strong>Model:</strong> claude-3-haiku-20240307<br>
                                    <strong>Cost:</strong> ~$0.016 per 1K tokens
                                </small>
                                <button type="button" class="btn btn-sm btn-outline-primary mt-2 use-template" data-provider="claude">
                                    Use Template
                                </button>
                                <hr>
                            </div>

                            <div class="provider-template" data-provider="openai">
                                <h6><i class="fas fa-robot text-success mr-2"></i>OpenAI (ChatGPT)</h6>
                                <small class="text-muted">
                                    <strong>Endpoint:</strong> https://api.openai.com/v1/chat/completions<br>
                                    <strong>Model:</strong> gpt-3.5-turbo<br>
                                    <strong>Cost:</strong> $0.002 per 1K tokens
                                </small>
                                <button type="button" class="btn btn-sm btn-outline-primary mt-2 use-template" data-provider="openai">
                                    Use Template
                                </button>
                                <hr>
                            </div>

                            <div class="provider-template" data-provider="gemini">
                                <h6><i class="fas fa-gem text-warning mr-2"></i>Gemini (Google)</h6>
                                <small class="text-muted">
                                    <strong>Endpoint:</strong> https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent<br>
                                    <strong>Model:</strong> gemini-pro<br>
                                    <strong>Cost:</strong> ~$0.0005 per 1K tokens
                                </small>
                                <button type="button" class="btn btn-sm btn-outline-primary mt-2 use-template" data-provider="gemini">
                                    Use Template
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const templates = {
        claude: {
            display_name: 'Claude (Anthropic)',
            endpoint: 'https://api.anthropic.com/v1/messages',
            model: 'claude-3-haiku-20240307',
            daily_limit: 1000,
            monthly_limit: 25000
        },
        openai: {
            display_name: 'ChatGPT (OpenAI)',
            endpoint: 'https://api.openai.com/v1/chat/completions',
            model: 'gpt-3.5-turbo',
            daily_limit: 1500,
            monthly_limit: 40000
        },
        gemini: {
            display_name: 'Gemini (Google)',
            endpoint: 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent',
            model: 'gemini-pro',
            daily_limit: 2000,
            monthly_limit: 50000
        }
    };

    // Toggle API key visibility
    $('#toggle-api-key').click(function() {
        const input = $('#api_key');
        const icon = $(this).find('i');
        
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Use template
    $('.use-template').click(function() {
        const provider = $(this).data('provider');
        const template = templates[provider];
        
        if (template) {
            $('#name').val(provider);
            $('#display_name').val(template.display_name);
            $('#endpoint').val(template.endpoint);
            $('#model').val(template.model);
            $('#daily_limit').val(template.daily_limit);
            $('#monthly_limit').val(template.monthly_limit);
        }
    });

    // Auto-fill when provider is selected
    $('#name').change(function() {
        const provider = $(this).val();
        const template = templates[provider];
        
        if (template && !$('#display_name').val()) {
            $('#display_name').val(template.display_name);
            $('#endpoint').val(template.endpoint);
            $('#model').val(template.model);
            $('#daily_limit').val(template.daily_limit);
            $('#monthly_limit').val(template.monthly_limit);
        }
    });
});
</script>
@endpush
