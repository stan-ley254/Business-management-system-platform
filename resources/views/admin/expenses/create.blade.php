<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    @include('admin.css')
    <style>
        .sidebar {
            position: fixed;
        }
        .form_color {
            color: #ffffff;
        }
    </style>
</head>
<body>
@include('admin.sidebar')
@include('admin.header')
    <div class="main-panel">
      
        <div class="content-wrapper">
            <div class="message d-print-inline-flex rounded">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
            <div class="container-md mt-2">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-success">Add Expenses </h4>
                        <form action="{{ isset($expense) ? route('other-incomes.update', $expense) : route('other-incomes.store') }}" method="POST">
    @csrf
    @if(isset($expense)) @method('PUT') @endif
                            <div class="form-group">
                                <label for="name" class="text-success">Name</label>
                                <input type="text" class="input-group" id="name" name="name" value="{{ $expense->name ?? '' }}" required>
                               
                            </div>
                            <div class="form-group">
                                <label for="amount" class="text-success">Amount</label>
                                <input type="text" class="input-group" id="amount" name="amount" value="{{ $expense->amount ?? '' }}" required>
                             
                            </div>
                            <div class="form-group">
                                <label for="date" class="text-success">Date</label>
                                <input type="date" class="input-group" id="date" name="date" value="{{ $expense->date ?? '' }}"  required>
                             
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.script')
</body>
</html>
