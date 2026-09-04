<?php

namespace App\Services;

use App\Models\MarketingCampaign;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MarketingCampaignService
{
    public function createCampaign(array $data): MarketingCampaign
    {
        return MarketingCampaign::create([
            'name' => $data['name'] ?? 'New Campaign',
            'type' => $data['type'] ?? 'notification',
            'status' => $data['status'] ?? 'draft',
            'audience' => $data['audience'] ?? null,
            'scheduled_at' => $data['scheduled_at'] ?? null,
            'content' => $data['content'] ?? null,
        ]);
    }

    public function updateStatus(int $campaignId, string $status): bool
    {
        return DB::transaction(function () use ($campaignId, $status) {
            $campaign = MarketingCampaign::findOrFail($campaignId);

            $validStatuses = ['draft', 'scheduled', 'running', 'completed'];
            if (!in_array($status, $validStatuses)) {
                return false;
            }

            $campaign->status = $status;

            if ($status === 'running' && $campaign->scheduled_at) {
                $campaign->sent_at = now();
            }

            $campaign->save();

            return true;
        });
    }

    public function getCampaigns(string $type = null, string $status = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = MarketingCampaign::query();

        if ($type) {
            $query->where('type', $type);
        }

        if ($status) {
            $query->where('status', $status);
        }

        return $query->latest('created_at')->get();
    }

    public function sendCampaign(int $campaignId): bool
    {
        return DB::transaction(function () use ($campaignId) {
            $campaign = MarketingCampaign::findOrFail($campaignId);

            if ($campaign->status !== 'running') {
                $this->updateStatus($campaignId, 'running');
            }

            // Send based on type
            switch ($campaign->type) {
                case 'email':
                    // Use Laravel mail - development-safe mode
                    // Mail::to($recipients)->send(new CampaignMail($campaign));
                    Log::info("Email campaign sent: {$campaign->name}", [$campaign->id]);
                    break;
                case 'notification':
                    // Use Laravel notification
                    // User::whereIn('ids', $recipients)->each(fn($u) => $u->notify(new CampaignNotification($campaign)));
                    Log::info("Notification sent: {$campaign->name}", [$campaign->id]);
                    break;
                case 'promotion':
                    // Trigger promotion logic
                    Log::info("Promotion campaign sent: {$campaign->name}", [$campaign->id]);
                    break;
            }

            $this->updateStatus($campaignId, 'completed');

            return true;
        });
    }
}