@extends('layouts.app')
@section('title','Edit/Statistics')
@section('content')
    <div class="xs">
        <h3>Edit Portal Statistics</h3>
        <div class="well1 white">
            <form class="form-floating ng-pristine ng-invalid ng-invalid-required ng-valid-email ng-valid-url ng-valid-pattern" novalidate="novalidate" ng-submit="submit()">
                <fieldset>
                    <div class="form-group">
                        <label class="control-label">Email</label>
                        <input type="email" class="form-control1 ng-invalid ng-valid-email ng-invalid-required ng-touched" id="username" name="username" />
                    </div>
                    <div class="form-group">
                        <label class="control-label">Name</label>
                        <input type="text" class="form-control1 ng-invalid ng-invalid-required ng-touched" id="name" name="name" />
                    </div>
                    <div class="form-group">
                        <label class="control-label">Password</label>
                        <input type="password" class="form-control1 ng-invalid ng-invalid-required ng-touched" id="password" name="password" />
                    </div>
                    <div class="form-group">
                        <label class="control-label">Confirm Password</label>
                        <input type="password" class="form-control1 ng-invalid ng-invalid-required ng-touched" id="cpassword" name="cpassword" />
                    </div>
                    <div class="form-group filled">
                        <label class="control-label">Role</label>
                        <select class="form-control1 ng-invalid ng-invalid-required" name="role_id" id="role_id">
                            <option value="">Please Select Role</option>
                            <option value="Select a pirate">Select a pirate</option>
                            <option value="Monkey D. Luffy">Monkey D. Luffy</option>
                            <option value="Roronoa Zoro">Roronoa Zoro</option>
                            <option value="Tony Tony Chopper">Tony Tony Chopper</option>
                            <option value="Nico Robin">Nico Robin</option>
                            <option value="Bon Clay">Bon Clay</option>
                        </select>
                    </div>
                    <div class="form-group filled">
                        <label class="control-label">Client</label>
                        <select class="form-control1 ng-invalid ng-invalid-required" name="client_id" id="client_id">
                            <option value="">Please Select Client</option>
                            <option value="Select a pirate">Select a pirate</option>
                            <option value="Monkey D. Luffy">Monkey D. Luffy</option>
                            <option value="Roronoa Zoro">Roronoa Zoro</option>
                            <option value="Tony Tony Chopper">Tony Tony Chopper</option>
                            <option value="Nico Robin">Nico Robin</option>
                            <option value="Bon Clay">Bon Clay</option>
                        </select>
                    </div>
                    <div class="form-group filled">
                        <label class="control-label">Supplie</label>
                        <select class="form-control1 ng-invalid ng-invalid-required" name="supplier_id" id="supplier_id">
                            <option value="">Please Select Supplie</option>
                            <option value="Select a pirate">Select a pirate</option>
                            <option value="Monkey D. Luffy">Monkey D. Luffy</option>
                            <option value="Roronoa Zoro">Roronoa Zoro</option>
                            <option value="Tony Tony Chopper">Tony Tony Chopper</option>
                            <option value="Nico Robin">Nico Robin</option>
                            <option value="Bon Clay">Bon Clay</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="checkbox1">
                            <label><input type="checkbox" class="ng-invalid ng-invalid-required">&nbsp;Active</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" onclick="myFunction()">Submit</button>
                        <button type="reset" class="btn btn-default">Reset</button>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
@endsection
