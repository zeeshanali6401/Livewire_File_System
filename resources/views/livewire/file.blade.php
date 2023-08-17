<div>
    @livewireStyles

    {{-- Top/Header (Button, search bar, icons) --}}
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mt-4 mb-2">
            <div class="d-flex">
                <button type="button" class="btn btn-primary btn-sm  mr-3 d-mx-auto d-block"
                    wire:click="show_modal">Add</button>
                <div class="input-group-sm">
                    <input class="form-control py-2 border-right-0 border" type="search" wire:model.debouce.1500ms="term"
                        placeholder="Search by Name or ID" id="example-search-input">
                </div>
            </div>
            <div>
                @if (session()->has('message'))
                    <span class="alert alert-success mr-3" style="">{{ session('message') }}</span>
                @elseif(session()->has('delmsg'))
                    <span class="alert alert-danger mr-3" style="">{{ session('delmsg') }}</span>
                @endif
            </div>
            <div>
                <ul class="list-group list-group-horizontal">
                    <li class="list-group-item border-0"><img width="30px" src="{{ asset('source/pdf.png') }}" alt="img"><span class="font-weight-bold pl-2">{{ $pdfFileCount }}</span></li>
                    <li class="list-group-item border-0"><img width="30px" src="{{ asset('source/img.png') }}" alt="img"><span class="font-weight-bold pl-2">{{ $pngFileCount }}</span></li>
                    <li class="list-group-item border-0"><img width="30px" src="{{ asset('source/doc.png') }}" alt="img"><span class="font-weight-bold pl-2">{{ $docxFileCount }}</span></li>
                    <li class="list-group-item border-0"><img width="30px" src="{{ asset('source/xls.png') }}" alt="img"><span class="font-weight-bold pl-2">{{ $xlsxFileCount }}</span></li>
                    <li class="list-group-item border-0"><img width="30px" src="{{ asset('source/pptx.png') }}" alt="img"><span class="font-weight-bold pl-2">{{ $pptxFileCount }}</span></li>
                  </ul>
            </div>
        </div>
        
    </div>
    <div class="container">
        {{ $selectAll }}
        <table class="table table-bordered">
            <thead class="text-center">
                <tr>
                    <th>ID</th>
                    <th style="width: 90px">
                        <button wire:click="delete" class="btn btn-danger btn-sm m-0" @if (!$bulkDlt) disabled @endif>Delete {{ count($bulkDlt) }}</button>
                        <input type="checkbox" wire:model.toggle="selectAll">
                    </th>
                    <th>Name</th>
                    <th>File</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody class="container text-center">
                @foreach ($collection as $item)
                    <tr>
                        <td class="text-center">{{ $item->id }}</td>
                        <td><input type="checkbox" value="{{ $item->id }}" @if ($selectAll) checked @endif wire:model="bulkDlt"></td>
                        <td>{{ $item->name }}</td>
                        <td>
                            <div class="d-flex justify-content-center"
                                style="display: flex;
                                justify-content: center;">
                                {{-- <img src="{{ asset('uploads/file_uploads') }}/{{ $item->file }}" width="100px" height="70px" alt="img" class="rounded d-block"> --}}
                                
                                    @php
                                        $extension = pathinfo($item->file, PATHINFO_EXTENSION);
                                    @endphp
    
                                    @if ($extension === 'pdf')
                                        <img width="40px" src="{{ asset('source/pdf.png') }}" alt="img">
                                    @elseif($extension === 'png')
                                        <img width="40px" src="{{ asset('source/png.png') }}" alt="img">
                                    @elseif($extension === 'jpg')
                                        <img width="40px" src="{{ asset('source/jpg.png') }}" alt="img">
                                    @elseif($extension === 'docx' || $extension === 'doc')
                                        <img width="40px" src="{{ asset('source/doc.png') }}" alt="img">
                                    @elseif($extension === 'xlsx' || $extension === 'xls')
                                        <img width="40px" src="{{ asset('source/xls.png') }}" alt="img">
                                    @elseif($extension === 'pptx' || $extension === 'ppt')
                                        <img width="40px" src="{{ asset('source/pptx.png') }}" alt="img">
                                    @endif
                            </div>
                        </td> 
                        <td class="text-center w-25">
                            <button class="btn btn-warning btn-sm" wire:click="downloads({{ $item->id }})"><img width="20px" src="{{ asset('source/download_svg.svg') }}"></button>
                            <button class="btn btn-sm btn-info" wire:click="edit({{ $item->id }})"><img width="20px" src="{{ asset('source/edit_svg.svg') }}"></button>
                            <button class="btn btn-sm btn-danger text-white" wire:click="deleteModalShow({{ $item->id }})"><img width="20px" src="{{ asset('source/delete_svg.svg') }}"></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
    
        </table>
        <div class="row">
            <div class="col-sm">
                {!! $collection->links() !!}
            </div>
            <div class="col-sm-3">
                Showing {{ $pagination['from'] }} to {{ $pagination['to'] }} of {{ $pagination['total'] }} users
            </div>
            
        </div>
    </div>

    {{-- Add Modal --}}
    <div wire:ignore.self class="modal fade" id="addUserModal">
        <div class="modal-dialog modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="exampleModalLongTitle">Add User</h5>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="submit">
                        <input class="form-control" type="text" name="name" wire:model="name" placeholder="Name">
                        @error('name')
                            <span style="color: red" class="error">{{ $message }}</span>
                        @enderror
                        <br>
                        <input class="form-control" type="file" name="file" wire:model="file">
                        @error('file')
                            <span style="color: red" class="error">{{ $message }}</span>
                        @enderror
                        <br>
                        {{-- <div class="text-center">
                            @if ($file)
                                <img src="{{ $file->temporaryUrl() }}" width="300" alt="" class="m-2">
                            @endif
                        </div> --}}
                        <div class="modal-footer">
                            <button class="btn btn-sm btn-primary m-2" wire:loading.attr="disabled" wire:target="file" type="submit">Submit</button><br>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div wire:ignore.self class="modal fade" id="showModalDlt">
        <div style="width: 300px" class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="text-center">
                    <h4 class="text-danger">Deleted Successfully</h4>
                    <img src="{{ asset('source/deleteImg.gif') }}" alt="Img">
                </div>
            </div>
        </div>
    </div>
    {{-- Delete Modal Confirmation --}}
    <div wire:ignore.self class="modal fade" id="deleteModalShow">
        <div style="width: 250px" class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-danger">
                    <h5 class="modal-title text-center" id="deleteModalShowLongTitle">Are you sure to delete?</h5>
                </div>
                <div class="modal-body text-center">
                    <button type="button" wire:click="deleteSingle" class="btn btn-danger">Yes</button>
                    <button type="button" class="btn btn-secondary" wire:click="deleteModalHide">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Update Modal --}}
    <div wire:ignore.self class="modal fade" id="updateModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center" id="updateModalLongTitle">Update <span
                            class="text-warning">{{ $uname }}</span></h5>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="update({{ $uid }})">
                        <label for="uname">Name</label>
                        <input class="form-control" type="text" name="uname" wire:model="uname"
                            placeholder="Update Name">
                        @error('uname')
                            <span style="color: red" class="error">{{ $message }}</span>
                        @enderror
                        <br>
                        <label for="ufile">file</label>
                        <input class="form-control" type="file" name="ufile" wire:model="ufile">
                        @error('ufile')
                            <span style="color: red" class="error">{{ $message }}</span>
                        @enderror
                        <br>
                        {{-- <div class="text-center">
                            <img src="{{ asset('uploads/file_uploads') }}/{{ $ufile }}" width="300"
                                alt="" class="m-2">
                        </div> --}}
                        <div class="modal-footer">
                            <button class="btn btn-sm btn-primary m-2" wire:loading.attr="disabled" wire:target="ufile" type="submit">Update</button><br>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @livewireScripts
</div>
<script src="{{ asset('js/custom_scripts.js') }}"></script>
</div>
