<!DOCTYPE html>
<html>
    <head>
        <title>Parser Repository</title>
        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
        <!-- Import the basic style sheet for the approach -->
        <link href="{{ URL::asset('assets/css/basic.css') }}" rel="stylesheet" type="text/css">
    </head>
    <body>
        <!-- Header template -->
        @include('templates.header')
        <!-- Body content -->
        <div class="container">
            <div class="content">
                <!-- Display error message in case of error found -->
                @if (Session::has('notification_msg'))                  
                    <div class="notification_message border-radius<?php if(Session::has('error')){echo " error";}else{echo " success";}?>">
                        {!! session('notification_msg') !!}
                    </div>
                @endif                
                <h4>
                    Please, provide an XML File that match the requirements <br />
                    (received from Online Educational Tool Provider):
                    <form action="upload-file" method="post" name="upload_file" class="upload-file"  enctype="multipart/form-data">
                        <div class="upload-file-type">
                            <label class="file-container border-radius cursor">
                                Click here to upload a file
                                <input type="file" class="cursor" name="xmlconfig" required="" />
                            </label>
                        </div>
                        <!-- Security token value for form submission -->
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <br />
                        <input type="submit" name="submit_xmlconfig" class="border-radius cursor" name="Submit File" value="Submit File" />
                    </form>
                </h4>
            </div>
        </div> 
        <!-- Footer template -->
        @include('templates.footer')
    </body>
</html>
