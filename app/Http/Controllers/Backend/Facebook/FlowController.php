<?php

namespace App\Http\Controllers\Backend\Facebook;

use Illuminate\Http\Request;
use App\Models\Data\FacebookFlow;
use App\Http\Controllers\Controller;
use App\Models\FacebookFlow as FlowModel;
use App\Models\Repositories\FacebookFlowRepository;

class FlowController extends Controller
{
    

    public function index()
    {
        $facebook = new FlowModel();
        $facebook = $facebook->findOne();
        
        if (!$facebook) {
            $facebook = new \stdClass();
            $facebook->_id = null;
            $facebook->flow = null;
        }

        $view_data = [
            'page_title' => 'Facebook Messenger flow',
            'facebook' => $facebook,
        ];
        return view('backend.pages.facebook.flow-form', $view_data);
    }

    /**
     * Create/save xml flow to database
     * 
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCreateFlow(Request $request, FacebookFlowRepository $facebookFlowRepository)
    {
        try{
            $facebookFlowRepository->save($this->extractFlowData($request));
            return response()->json('Flow successfully saved!', 200);
        }catch(\Exception $e){
            return response()->json('Error while saving flow. Please try again!', 400);
        }
    }

    private function extractFlowData(Request $request)
    {
        $data = new FacebookFlow();

        $data->setFlow($request->flow);

        $facebook = new FlowModel();
        $facebook = $facebook->findOne();

        if ($facebook) {
            $data->setId($facebook->_id->__toString());
        }

        return $data;
    }
}
