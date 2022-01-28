<?php

/**
 * @todo:: Refactor all repositories.
 * Instead of creating repository every single module, we should
 * create just one repository with save() method. The parameter
 * of this method should be \App\Models\Api\DataInterface
 */

namespace App\Models\Repositories;

use App\Models\Chatter;
use App\Models\Repositories\BaseRepository;

class LiveChatRepository extends BaseRepository
{


    public function getDataTableData()
    {
        $limit = $this->getDataTableLimit();
        $offset = $this->getDataTableOffset();
        list($column, $direction) = $this->getDataTableOrder();

        /*
        $chats = Chatter::where(function($query){
                                $search = $this->getDataTableSearch();
                                if ($search) {
                                    $query->where('fullname', 'like', '%' . $search . '%');;
                                }
                           })
                           ->limit($limit)
                           ->offset($offset)
                           ->orderBy($column, $direction)
                           ->get();
        */

        $chats = new Chatter();
        $chats = $chats->find()->toArray();
        $request = $this->getRequest();
        $totalRecords = count($chats);
        $data = [
            'draw' => $request->draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => []
        ];
        
        $_chats = [];

        foreach($chats as $chat){
            
            $_chats[] = [
                'fullname' => $chat['fullname'],
                'edit_action' => '<a href="' . route('admin.config.messenger.message.view', ['id' => $chat['_id']->__toString()]) . '" class="fa fa-commenting-o"></a>',
            ];
            
        }

        $data['data'] = $_chats;

        return $data;

    }
}