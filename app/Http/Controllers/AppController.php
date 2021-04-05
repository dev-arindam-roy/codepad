<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use App\Models\Tab;

class AppController extends Controller
{
    public function getAllTabs(Request $request)
    {
        $response = [];
        $tabs = Tab::orderBy('order_no', 'asc');
        if ($request->has('name') && $request->get('name') != '') {
            $tabs = $tabs->where('name', 'LIKE', '%' . $request->get('name') . '%');
        }
        if ($request->has('pagination')) {
            $tabs = $tabs->paginate($request->get('pagination'));
        } else {
            $tabs = $tabs->all();
        }
        $lastEntry = [];
        if (!empty($tabs) && count($tabs)) {
            foreach ($tabs as $tb) {
                if (isset($tb->noteInfo) && !empty($tb->noteInfo)) {
                    $lastEntry[$tb->id] = $tb->noteInfo->created_at;
                } else {
                    $lastEntry[$tb->id] = $tb->created_at;
                }
            }
        }
        $response['status'] = 200;
        $response['type'] = 'success';
        $response['msg'] = '';
        $response['content'] = [
            'tabList' => $tabs,
            'lastEntry' => $lastEntry
        ];
        return response()->json($response, 200);
    }

    public function saveTab(Request $request)
    {
        $response = [];
        $id = $request->input('id');
        $name = $request->input('name');
        $chkName = Tab::where('name', $name)->where('id', '!=', $id)->exists();
        if ($chkName) {
            $response['status'] = 200;
            $response['type'] = 'error';
            $response['msg'] = 'Tab name already exists.';
            $response['content'] = [];
            return response()->json($response, 200);
        }
        if ($id == 0) {
            $tab = new Tab;
            $response['msg'] = 'Tab created successfully.';
        } else {
            $tab = Tab::find($id);
            $response['msg'] = 'Saved changes successfully.';
        }
        $tab->name = ucwords($name);
        $tab->save();
        $response['status'] = 200;
        $response['type'] = 'success';
        $response['content'] = [
            'tabInfo' => $tab
        ];
        return response()->json($response, 200);
    }

    public function getTabsAndNotes(Request $request)
    {
        $response = [];
        $note = [];
        $tabs = Tab::orderBy('order_no', 'asc')->get();
        if (!empty($tabs) && count($tabs)) {
            $firstTab = $tabs[0]->id;
            $firstNote = Note::where('tab_id', $firstTab)->first();
            if (!empty($firstNote)) {
                $firstNote->note_content = html_entity_decode($firstNote->note_content, ENT_QUOTES);
                if (!empty($firstNote)) {
                    $note = $firstNote; 
                }
            }

        }
        $response['status'] = 200;
        $response['type'] = 'success';
        $response['msg'] = '';
        $response['content'] = [
            'tabList' => $tabs,
            'firstNote' => $note
        ];
        return response()->json($response, 200); 
    }

    public function saveNote(Request $request)
    {
        $id = $request->input('id');
        if ($id == 0) {
            $note = new Note;
            $note->tab_id = $request->input('tab_id');
        } else {
            $note = Note::find($id);
        }
        $note->note_content = htmlentities($request->input('note_content'), ENT_QUOTES);
        $note->save();
        $response = [];
        $response['status'] = 200;
        $response['type'] = 'success';
        $response['msg'] = 'Saved Successfully.';
        $response['content'] = [
            'note' => $note
        ];
        return response()->json($response, 200);
    }

    public function getNoteByTabId(Request $request)
    {
        $response = [];
        $note = Note::where('tab_id', $request->input('tab_id'))->first();
        if (empty($note)) {
            $response['status'] = 200;
            $response['type'] = 'error';
            $response['msg'] = '';
            $response['content'] = [];
            return response()->json($response, 200);
        }
        $note->note_content = html_entity_decode($note->note_content, ENT_QUOTES);
        $response['status'] = 200;
        $response['type'] = 'success';
        $response['msg'] = '';
        $response['content'] = [
            'note' => $note
        ];
        return response()->json($response, 200);
    }

    public function deleteTab(Request $request)
    {
        $response = [];
        $tab = Tab::find($request->input('tab_id'));
        $tabInfo = $tab;
        if (empty($tab)) {
            $response['status'] = 200;
            $response['type'] = 'error';
            $response['msg'] = 'Something went wrong';
            $response['content'] = [];
            return response()->json($response, 200);
        }
        $tab->delete();
        Note::where('tab_id', $request->input('tab_id'))->delete();

        $response['status'] = 200;
        $response['type'] = 'success';
        $response['msg'] = 'Tab deleted successfully.';
        $response['content'] = [
            'deletedTab' => $tabInfo
        ];
        return response()->json($response, 200);
    }

    public function setTabOrder(Request $request)
    {
        $response = [];
        $orderArr = $request->input('tab_order');
        if (!empty($orderArr)) {
            foreach ($orderArr as $k => $v) {
                $tab = Tab::find($k);
                if (!empty($tab)) {
                    $tab->order_no = $v;
                    $tab->save();
                }
            }
        }
        $response['status'] = 200;
        $response['type'] = 'success';
        $response['msg'] = 'Tab order set successfully.';
        $response['content'] = [];
        return response()->json($response, 200);
    }
}
