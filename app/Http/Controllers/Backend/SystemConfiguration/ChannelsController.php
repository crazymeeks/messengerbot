<?php

namespace App\Http\Controllers\Backend\SystemConfiguration;

use App\Models\Channel;
use Illuminate\Http\Request;
use App\Models\Api\ChannelInterface;
use App\Http\Controllers\Controller;
use App\Models\Repositories\ChannelRepository;

class ChannelsController extends Controller
{
    
    protected $channel;

    public function __construct(ChannelInterface $channel)
    {
        $this->channel = $channel;
    }

    public function index()
    {
        $view_data = [
            'page_title' => 'Channels',
            'channels' => Channel::get(),
        ];
        return view('backend.pages.configuration.channels.index', $view_data);
    }

    /**
     * Add new channels
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Repositories\ChannelRepository $channelRepository
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function addChannels(Request $request, ChannelRepository $channelRepository)
    {
        
        $request->validate([
            'type' => 'required',
            'access_token' => 'required',
            'name' => 'required',
            'id' => 'required',
        ]);
        try{
            $this->channel->setChannel($request);
            $channelRepository->save($this->channel);
            return response()->json(['success' => 'Channel successfully added.'], 201);
        }catch(\App\Models\Repositories\Exceptions\ChannelAlreadyExistException $e){
            return response()->json(['error' => $e->getMessage()], 400);
        }catch(\Exception $e){
            \Log::info('Add channels error: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to add channel. Please try again'], 400);
        }
    }
}
