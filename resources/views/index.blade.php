@extends('layouts.master')
@section('content')   
   <div class="section">
<div class="section no-pad-bot" id="index-banner">
    <div class="container">
      <br><br>
      <h1 class="header center orange-text">Order management tool</h1>
      <div class="row center">
        <h5 class="header col s12 light">Manage quote and orders accross all properties and suppliers</h5>
      </div>
      <div class="row center">
        <a href="{{route('enter_order')}}" id="download-button" class="btn-large waves-effect waves-light orange">Enter an order</a>
      </div>
      <br><br>

    </div>
  </div>
      <!--   Icon Section   -->
      <div class="row">
        <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center light-blue-text"><i class="material-icons">flash_on</i></h2>
            <h5 class="center">Minimize cost</h5>

            <p class="light">Allow to have a comprehensive overview of the pending order by supplier in order to organize in the best possible way the transport.</p>
          </div>
        </div>

        <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center light-blue-text"><i class="material-icons">group</i></h2>
            <h5 class="center">Minimize delay</h5>

            <p class="light">Keeping track of the order status in a centralized system will allow to be more responsive and order items as soon as condition are met.</p>
          </div>
        </div>

        <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center light-blue-text"><i class="material-icons">settings</i></h2>
            <h5 class="center">Flexibility</h5>

            <p class="light">Easily update order status and amend schedule delivery date to keep track of changes. In the future the app will be connected with the REST API of the webstore to update the order status in real time from a single endpoint</p>
          </div>
        </div>
      </div> 
	</div>
@endsection('content')