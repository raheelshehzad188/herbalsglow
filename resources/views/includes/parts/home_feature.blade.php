<style>
    .font-size-custom {
        font-size: 15px; /* Adjust the font size as needed */
    }
</style>

<div class="container-fluid pt-5">
    <div class="row mx-xl-5 align-items-center" style="border:1px solid #ebebeb;">
        @foreach($boxes as $k => $v)
            <div class="col-md-3 col-6" style="border-right:1px solid #ebebeb;">
                <div class="d-flex align-items-center bg-light" style="padding: 20px;">
                    <h1 class="fa fa-{{$v->icon}} text-primary m-0 mr-3"></h1>
                    <h5 class="font-weight-semi-bold m-0 font-size-custom">{{$v->txt}}</h5>
                </div>
            </div>
        @endforeach
        </div>
</div>