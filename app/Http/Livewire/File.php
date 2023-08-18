<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\File as Files;
use Carbon\Carbon;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\File as Filo;

class File extends Component
{
    use WithFileUploads;
    use WithPagination;
    public $selectedIds = [];
    public $name, $validatedData, $file, $fileName;
    public $uid, $uname, $ufile, $term = null, $file_id;
    public $bulkDlt = [], $selectAll = false, $firstId = NULL;
    protected $listeners = ['resetbulkDlt'=>'resetSelected'];
    public $data, $pngFileCount, $pdfFileCount, $docxFileCount, $xlsxFileCount, $pptxFileCount;
    protected $paginationTheme = 'bootstrap';
 
    public function mount()
    {
        $this->data = Files::all();
        $collectionArray = $this->data->toArray();

        $this->pdfFileCount = count(array_filter($collectionArray, function ($item) {
            $filePath = $item['file'];
            return pathinfo($filePath, PATHINFO_EXTENSION) === 'pdf';
        }));
        $this->pngFileCount = count(array_filter($collectionArray, function ($item) {
            $filePath = $item['file'];
            return pathinfo($filePath, PATHINFO_EXTENSION) === 'png' || pathinfo($filePath, PATHINFO_EXTENSION) === 'jpg';
        }));
        $this->docxFileCount = count(array_filter($collectionArray, function ($item) {
            $filePath = $item['file'];
            return pathinfo($filePath, PATHINFO_EXTENSION) === 'docx' || pathinfo($filePath, PATHINFO_EXTENSION) === 'doc';
        }));
        $this->xlsxFileCount = count(array_filter($collectionArray, function ($item) {
            $filePath = $item['file'];
            return pathinfo($filePath, PATHINFO_EXTENSION) === 'xlsx' || pathinfo($filePath, PATHINFO_EXTENSION) === 'xls';
        }));
        $this->pptxFileCount = count(array_filter($collectionArray, function ($item) {
            $filePath = $item['file'];
            return pathinfo($filePath, PATHINFO_EXTENSION) === 'ppt' || pathinfo($filePath, PATHINFO_EXTENSION) === 'pptx';
        }));
    }
    public function render()
    {
        $searchTerm = '%' . $this->term . '%';
        $collection = Files::where('name', 'LIKE', $searchTerm)->orwhere('id', 'LIKE', $searchTerm)->paginate(5); // ->latest()
        $this->firstId = $collection[0]->id;
        return view('livewire.file', [
            'collection' => $collection,
            'pagination' => $collection->toArray(),
        ]);
        $this->mount();
    }
    public function updatedSelectAll($value){
        if($value){
            $this->bulkDlt = Files::where('id', '>=', $this->firstId)->limit(5)->pluck('id');
        }else{
            $this->bulkDlt = [];
        }
    }
    public function updatedbulkDlt($value)
    {
        if(count($value) == 5){
            $this->selectAll = true;
        }else{
            $this->selectAll = false;
        }
    }
    public function resetSelected()
    {
        $this->bulkDlt = [];
        $this->selectAll = false;
    }
    public function selectAll($ids){
        $this->selectedIds = explode(',', $ids);
        dd($ids);
    }
    public function updated($field)
    {
        $this->validateOnly($field, [
            'name' => 'required',
            'file' => 'required|file|mimes:png,jpg,xls,xlsx,doc,docx,ppt,pptx,pdf|max:1024',
        ]);
    }
    public function show_modal()
    {
        if (!is_null([$this->name, $this->file])) {
            $this->name = null;
            $this->file = null;
        }
        $this->dispatchBrowserEvent('showModal');
        $this->render();
    }
    public function resetData()
    {
        $this->name = null;
        $this->file = null;
    }
    public function submit()
    {
        $this->validate([
            'name' => 'required',
            'file' => 'required|file|mimes:png,jpg,xls,xlsx,doc,ppt,pptx,pdf|max:1024',
        ]);

        $file = new Files;
        $fileName = Carbon::now()->timestamp . '.' . $this->file->extension();
        $this->file->storeAs('file_uploads', $fileName);
        $file->name = $this->name;
        $file->file = $fileName;
        $file->save();

        $this->dispatchBrowserEvent('closeModal');
        $this->resetData();
        session()->flash('message', 'Record has been successfully Added. 😀');
        $this->render();
        $this->mount();
    }
    // public function delete()
    // {
    //     $data = Files::find($this->bulkDlt);        // bulkDlt is an array which have stored the ids
    //     foreach ($data as  $value) {
    //         if (!is_null($data)) {
    //             $filePath = public_path('uploads/file_uploads') . '/' . $value->file;
    //             Filo::delete($filePath) && $value->delete();

    //         }
    //     }
    //     $this->dispatchBrowserEvent('showModalDlt');
    //     $this->bulkDlt = [];
    //     $this->resetData();
    //     $this->render();
    //     $this->mount();

    // }
    public function bulkDelete()
    {
        $data = Files::find($this->bulkDlt);        // bulkDlt is an array which have stored the ids
        foreach ($data as  $value) {
            if (!is_null($data)) {
                $filePath = public_path('uploads/file_uploads') . '/' . $value->file;
                Filo::delete($filePath) && $value->delete();
            }
            $value->delete();
        }
        $this->dispatchBrowserEvent('showModalDlt');
        $this->bulkDlt = [];
        $this->resetData();
        $this->resetSelected();
        $this->render();
        $this->mount();

    }
    public function deleteSingle()
    {
        $data = Files::find($this->file_id);
        if (!is_null($data)) {
            $filePath = public_path('uploads/file_uploads') . '/' . $data->file;
            if (Filo::exists($filePath)) {
                Filo::delete($filePath);
            }
            $this->dispatchBrowserEvent('deleteModalHide');
            $data->delete();
        }
        $this->dispatchBrowserEvent('showModalDlt');
        $this->bulkDlt = [];
        $this->resetData();
        $this->render();
        $this->mount();

    }
    public function deleteModalShow($id)
    {
        $this->file_id = $id;
        $this->dispatchBrowserEvent('deleteModalShow');
    }
    public function deleteModalHide()
    {
        $this->dispatchBrowserEvent('deleteModalHide');
    }
    public function edit($id)
    {
        $file = Files::find($id);
        $this->uid = $id;
        $this->uname = $file['name'];
        $this->ufile = $file['file'];

        $this->dispatchBrowserEvent('updateModalShow');
    }
    public function update($uid)
    {
        $this->validate([
            'uname' => 'required',
            'ufile' => 'required',
        ]);

        $file = Files::find($uid);

        $ufileName = Carbon::now()->timestamp . '.' . $this->ufile->extension();
        $this->ufile->storeAs('file_uploads', $ufileName);
        $file->name = $this->uname;
        $file->file = $ufileName;
        $file->save();

        $this->dispatchBrowserEvent('closeModal');
        $this->resetData();
        session()->flash('message', 'Record has been successfully Updated. 😀');
        $this->render();
    }
    public function downloads($id){
        $data = Files::find($id);
        return response()->download(public_path('uploads/file_uploads'). '/' . $data->file);
    }
}
