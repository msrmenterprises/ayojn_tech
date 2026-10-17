<style>
    .swal2-popup{
        font-size: 1.3rem !important;
        font-family:'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif !important;
    }
    .swal2-popup #swal2-content {
    text-align: left !important;
    
}
</style>
<table id="data-table" class="table table-bordered table-striped last-child-sm" width="100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Bank Country</th>
            <th>Currency</th>
            <th>Bank Account</th>
            <th>Bank Code</th>
            <th>Code No</th>
            <th>Tax ID</th>
            <th>Default Bank</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>
        @foreach($bank as $b)
            <tr>
                 <td>{{$loop->index+1}}</td>
                 <td>{{$b->my_bank_country}}</td>
                 <td>{{$b->my_currency}}</td>
                 <td>{{$b->my_bank_account}}</td>
                 <td>{{$b->my_bank_code}}</td>
                 <td>{{$b->my_code_no}}</td>
                 <td>{{$b->my_taxid}}</td>
                    <td>
                        @if($b->default_bank == 0)
                            <span class="badge badge-success">Primary</span>
                        @else
                            <span class="badge badge-danger">Other</span>
                        @endif
                    </td>
                 <td>{{$b->created_at}}</td>
            </tr>
        @endforeach
        @if($bank->count() == 0)
            <tr><td colspan="9">No bank information available.</td></tr>
        @endif
    </tbody>
</table>

