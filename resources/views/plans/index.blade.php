@extends('dashboard.layout')

@section('css')
<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
    }
    
    .page-wrapper {
        background: transparent !important;
    }

    .plans-header {
        text-align: center;
        margin-bottom: 60px;
        color: white;
        margin-top: 30px;
    }

    .plans-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .plans-header p {
        font-size: 1.1rem;
        opacity: 0.95;
        margin-bottom: 30px;
    }

    .plans-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 30px;
        margin-bottom: 60px;
    }

    .plan-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .plan-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    }

    .plan-card.featured {
        border: 3px solid #667eea;
        transform: scale(1.05);
    }

    .plan-card.featured:hover {
        transform: scale(1.05) translateY(-10px);
    }

    .plan-badge {
        position: absolute;
        top: 20px;
        right: -35px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 5px 40px;
        transform: rotate(45deg);
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        z-index: 1;
    }

    .plan-header {
        background: linear-gradient(135deg, #f5f7fa 0%, #f0f2f5 100%);
        padding: 30px 25px;
        border-bottom: 1px solid #e8ecf1;
    }

    .plan-name {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 10px;
    }

    .plan-description {
        font-size: 0.9rem;
        color: #666;
    }

    .plan-price {
        display: flex;
        align-items: baseline;
        gap: 5px;
        margin-top: 15px;
    }

    .plan-price .amount {
        font-size: 2.5rem;
        font-weight: 700;
        color: #667eea;
    }

    .plan-price .period {
        color: #999;
        font-size: 0.9rem;
    }

    .plan-body {
        padding: 30px 25px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .plan-features {
        list-style: none;
        margin-bottom: 30px;
    }

    .plan-features li {
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 12px;
        color: #555;
        font-size: 0.95rem;
    }

    .plan-features li:last-child {
        border-bottom: none;
    }

    .plan-features i {
        color: #667eea;
        font-size: 1rem;
    }

    .plan-features .text-muted {
        opacity: 0.5;
    }

    .plan-button {
        margin-top: auto;
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1rem;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }

    .plan-button.primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .plan-button.primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        color: white;
        text-decoration: none;
    }

    .plan-button.secondary {
        background: #f0f0f0;
        color: #667eea;
        border: 2px solid #667eea;
    }

    .plan-button.secondary:hover {
        background: #667eea;
        color: white;
        text-decoration: none;
    }

    .plan-button:disabled {
        cursor: not-allowed;
        opacity: 0.7;
    }

    .current-badge {
        display: inline-block;
        background: #28a745;
        color: white;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .usage-section {
        background: white;
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 40px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }

    .usage-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .usage-header h3 {
        font-size: 1.3rem;
        font-weight: 700;
        color: #1a1a1a;
    }

    .usage-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .usage-item {
        background: linear-gradient(135deg, #f5f7fa 0%, #f0f2f5 100%);
        padding: 20px;
        border-radius: 12px;
        text-align: center;
    }

    .usage-label {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 8px;
    }

    .usage-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: #667eea;
    }

    .usage-progress {
        margin-top: 10px;
        height: 6px;
        background: #e8ecf1;
        border-radius: 3px;
        overflow: hidden;
    }

    .usage-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        border-radius: 3px;
    }

    @media (max-width: 768px) {
        .plans-header h1 {
            font-size: 1.8rem;
        }

        .plan-card.featured {
            transform: scale(1);
        }

        .plan-card.featured:hover {
            transform: translateY(-10px);
        }

        .usage-grid {
            grid-template-columns: 1fr;
        }

        .plans-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- Header -->
            <div class="plans-header">
                <h1>Choose Your Perfect Plan</h1>
                <p>Unlock powerful features and scale your widgets effortlessly</p>
            </div>

            <!-- Current Usage Stats -->
            <div class="usage-section">
                <div class="usage-header">
                    <h3>Your Current Usage</h3>
                    <span class="badge bg-primary text-uppercase">{{ $user->plan->name ?? 'Free' }} Plan</span>
                </div>
                <div class="usage-grid">
                    <div class="usage-item">
                        <div class="usage-label">Widgets</div>
                        <div class="usage-value">{{ $widgetCount }} / {{ $widgetLimit }}</div>
                        <div class="usage-progress">
                            <div class="usage-progress-bar" style="width: {{ ($widgetCount / $widgetLimit) * 100 }}%"></div>
                        </div>
                    </div>
                    <div class="usage-item">
                        <div class="usage-label">Personalities</div>
                        <div class="usage-value">{{ $personalityCount }} / {{ $personalityLimit }}</div>
                        <div class="usage-progress">
                            <div class="usage-progress-bar" style="width: {{ ($personalityCount / $personalityLimit) * 100 }}%"></div>
                        </div>
                    </div>
                    <div class="usage-item">
                        <div class="usage-label">Daily Messages</div>
                        <div class="usage-value">{{ $todayUsage }} / {{ $dailyPromptLimit }}</div>
                        <div class="usage-progress">
                            <div class="usage-progress-bar" style="width: {{ ($todayUsage / $dailyPromptLimit) * 100 }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Plans Grid -->
            <div class="plans-grid">
                @foreach ($plans as $plan)
                    @php
                        $isCurrent = $user->plan && $user->plan->id === $plan->id;
                        $isPro = $plan->name === 'Pro' || $plan->name === 'pro';
                    @endphp

                    <div class="plan-card {{ $isPro ? 'featured' : '' }}">
                        @if ($isPro)
                            <div class="plan-badge">Popular</div>
                        @endif

                        <div class="plan-header">
                            <div class="plan-name">
                                {{ ucfirst($plan->name) }}
                                @if ($isCurrent)
                                    <span class="current-badge">Current</span>
                                @endif
                            </div>
                            <div class="plan-description">
                                @if ($plan->name === 'free')
                                    Get started with basic features
                                @elseif ($plan->name === 'pro')
                                    Perfect for growing projects
                                @else
                                    For large-scale operations
                                @endif
                            </div>
                            <div class="plan-price">
                                @if ($plan->price && $plan->price > 0)
                                    <span class="amount">${{ $plan->price }}</span>
                                    <span class="period">/ month</span>
                                @else
                                    <span class="amount">$0</span>
                                    <span class="period">forever</span>
                                @endif
                            </div>
                        </div>

                        <div class="plan-body">
                            <ul class="plan-features">
                                <li>
                                    <i class="fas fa-check-circle"></i>
                                    <span><strong>{{ $plan->widget_limit }}</strong> {{ $plan->widget_limit == 1 ? 'Widget' : 'Widgets' }}</span>
                                </li>
                                <li>
                                    <i class="fas fa-check-circle"></i>
                                    <span><strong>{{ $plan->personality_limit }}</strong> {{ $plan->personality_limit == 1 ? 'Personality' : 'Personalities' }}</span>
                                </li>
                                <li>
                                    <i class="fas fa-check-circle"></i>
                                    <span><strong>{{ $plan->daily_prompt_limit }}</strong> Daily messages</span>
                                </li>
                                <li @class(['text-muted' => $plan->name === 'free'])>
                                    @if ($plan->name !== 'free')
                                        <i class="fas fa-check-circle"></i>
                                    @else
                                        <i class="fas fa-times-circle"></i>
                                    @endif
                                    <span>
                                        @if ($plan->name === 'free')
                                            Community support
                                        @else
                                            Priority support
                                        @endif
                                    </span>
                                </li>
                                <li @class(['text-muted' => $plan->name === 'free'])>
                                    @if ($plan->name !== 'free')
                                        <i class="fas fa-check-circle"></i>
                                    @else
                                        <i class="fas fa-times-circle"></i>
                                    @endif
                                    <span>Analytics dashboard</span>
                                </li>
                            </ul>

                            @if ($isCurrent)
                                <button class="plan-button secondary" disabled>
                                    <span class="current-badge">Current Plan</span>
                                </button>
                            @else
                                <a href="mailto:admin@example.com?subject=Plan%20upgrade%20request&body=Hi,%20I%20would%20like%20to%20upgrade%20to%20the%20{{ ucfirst($plan->name) }}%20plan."
                                   class="plan-button primary">
                                    Request {{ ucfirst($plan->name) }} Plan
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
@endsection