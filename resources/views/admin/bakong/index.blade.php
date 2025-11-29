@extends('admin.layouts.app')
@section('title', 'Change Bakong KHQR')
@section('content')
<div>
    <nav>
        <ol class="breadcrumb">
            {{-- <li class="breadcrumb-item"><a href="{{ route('/') }}">@lang('admin_dash.home')</a></li> --}}
            <li class="breadcrumb-item">Change Bakong KHQR</li>
        </ol>
    </nav>

    @if (session('message'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            toastr.success(@json(session('message')));
        });
    </script>
    @endif
    
    {{-- <h1>Bakong KHQR Encode and Decode</h1> --}}
    {{-- <h4>Upload The screen shot image of bakong khqr</h4> --}}
    <!-- Upload QR Image Section -->
    

<!-- Upload  -->

    <div class="section" style="margin-bottom: 30px;">
        <h3>Image of Bakong KHQR</h3>
        {{-- <div class="input-group">
            <input type="file" id="file-upload" accept="image/*" class="form-control">
            <a class="btn btn-label-primary" href="{{url('admin/view-khqr')}}">View KHQR</a>
        </div> --}}
        <form id="file-upload-form" class="uploader">
            <input id="file-upload"  type="file" name="fileUpload" accept="image/*" />
          
            <label for="file-upload" id="file-drag">
              <img id="file-image" src="#" alt="Preview" class="hidden">
              <div id="start">
                <i class="fa fa-download" aria-hidden="true"></i>
                {{-- <i class="fa fa-download" aria-hidden="true"></i> --}}
                <div>Select a file or drag here</div>
                <div id="notimage" class="hidden">Please select an image</div>
                <span id="file-upload-btn" class="btn btn-primary">Select a file</span>
              </div>
              <div id="response" class="hidden">
                <div id="messages"></div>
                {{-- <progress class="progress" id="file-progress" value="0">
                  <span>0</span>%
                </progress> --}}
              </div>
            </label>
        </form>
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <!-- Display Decoded Data -->
    <div class="section" style="margin-bottom: 30px;">
        <h3>QR Data</h3>
        <div class="border">
            <textarea id="strToEncodeQr" class="form-control"></textarea>
        </div>
    </div>
</div>
<style>
    @import url(https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css);
    .uploader {
  display: block;
  clear: both;
  margin: 0 auto;
  width: 100%;
  /* max-width: 600px; */

  label {
    float: left;
    clear: both;
    width: 100%;
    padding: 2rem 1.5rem;
    text-align: center;
    background: #fff;
    border-radius: 7px;
    border: 3px solid #eee;
    transition: all .2s ease;
    user-select: none;

    &:hover {
      border-color: $theme;
    }
    &.hover {
      border: 3px solid $theme;
      box-shadow: inset 0 0 0 6px #eee;
      
      #start {
        i.fa {
          transform: scale(0.8);
          opacity: 0.3;
        }
      }
    }
  }

  #start {
    float: left;
    clear: both;
    width: 100%;
    &.hidden {
      display: none;
    }
    i.fa {
      font-size: 50px;
      margin-bottom: 1rem;
      transition: all .2s ease-in-out;
    }
  }
  #response {
    float: left;
    clear: both;
    width: 100%;
    &.hidden {
      display: none;
    }
    #messages {
      margin-bottom: .5rem;
    }
  }

  #file-image {
    display: inline;
    margin: 0 auto .5rem auto;
    width: auto;
    height: auto;
    max-width: 180px;
    &.hidden {
      display: none;
    }
  }
  
  #notimage {
    display: block;
    float: left;
    clear: both;
    width: 100%;
    &.hidden {
      display: none;
    }
  }

  progress,
  .progress {
    // appearance: none;
    display: inline;
    clear: both;
    margin: 0 auto;
    width: 100%;
    max-width: 180px;
    height: 8px;
    border: 0;
    border-radius: 4px;
    background-color: #eee;
    overflow: hidden;
  }

  .progress[value]::-webkit-progress-bar {
    border-radius: 4px;
    background-color: #eee;
  }

  .progress[value]::-webkit-progress-value {
    background: linear-gradient(to right, darken($theme,8%) 0%, $theme 50%);
    border-radius: 4px; 
  }
  .progress[value]::-moz-progress-bar {
    background: linear-gradient(to right, darken($theme,8%) 0%, $theme 50%);
    border-radius: 4px; 
  }

  input[type="file"] {
    display: none;
  }

  div {
    margin: 0 0 .5rem 0;
    color: $dark-text;
  }
  .btn {
    display: inline-block;
    margin: .5rem .5rem 1rem .5rem;
    clear: both;
    font-family: inherit;
    font-weight: 700;
    font-size: 14px;
    text-decoration: none;
    text-transform: initial;
    border: none;
    border-radius: .2rem;
    outline: none;
    padding: 0 1rem;
    height: 36px;
    line-height: 36px;
    color: #fff;
    transition: all 0.2s ease-in-out;
    box-sizing: border-box;
    background: $theme;
    border-color: $theme;
    cursor: pointer;
  }
}
</style>
@endsection

@push('scripts')
<script src="https://unpkg.com/@zxing/library@latest"></script>
<script src="https://cdn.bakongkhqr.com/lib/bakong-khqr-1.5.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
<script>
    $(document).ready(function () {
        const editorQr = CodeMirror.fromTextArea(document.getElementById('strToEncodeQr'), {
            lineNumbers: true,
            theme: "base16-light",
            mode: "application/json",
        });

        // Toastr Configuration (Optional)
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: "5000",
        };

        // QR Code Upload and Decoding
        $("#file-upload").change(function (event) {
            const file = event.target.files[0];
            if (!file) {
                toastr.error("Please upload an image file!", "Error");
                return;
            }

            const reader = new FileReader();
            reader.onload = function () {
                const img = new Image();
                img.src = reader.result;
                img.onload = function () {
                    const canvas = document.createElement('canvas');
                    const context = canvas.getContext('2d');
                    canvas.width = img.width;
                    canvas.height = img.height;
                    context.drawImage(img, 0, 0, canvas.width, canvas.height);

                    const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                    const decoded = jsQR(imageData.data, imageData.width, imageData.height);

                    if (decoded) {
                        processDecodedQR(decoded.data, editorQr);
                    } else {
                        toastr.warning("No QR code found in the image!", "Decoding Warning");
                    }
                };
            };
            reader.readAsDataURL(file);
        });

        // Process Decoded QR Data
        function processDecodedQR(qrData, editor) {
            try {
                const response = BakongKHQR.BakongKHQR.decode(qrData);

                if (response.status.code !== 0) {
                    toastr.error(`Error: ${response.status.message}`, "Decoding Error");
                    return;
                }

                const decodedData = response.data;
                editor.getDoc().setValue(JSON.stringify(decodedData, null, 2));

                // Send to Backend
                saveDecodedData(decodedData);
            } catch (error) {
                console.error("QR Decoding Error:", error);
                toastr.error("An error occurred while decoding the QR code.", "Error");
            }
        }
        
        // Save Decoded Data
        function saveDecodedData(data) {
            $.ajax({
                url: "{{ route('admin.saveDecodedData') }}",
                method: "POST",
                data: {
                    ...data,
                    merchantType: data.merchantType || "default",
                    _token: "{{ csrf_token() }}",
                },
                success: function (response) {
                    if (response.success) {
                        toastr.success(response.message, "Save Successful");
                    } else {
                        console.error("Save Failed:", response.error);
                        toastr.error("Save Failed: " + (response.error || "Unknown error"), "Error");
                    }
                },
                error: function (xhr) {
                    console.error("AJAX Error:", xhr.responseText);
                    toastr.error("An error occurred while saving the data.", "Save Error");
                },
            });
        }

        // Example: Generate QR Code (You can customize this part)
        // function generateQRCode() {
        //     toastr.info("QR Code generation initiated...", "Info");
            
        //     // Add your QR Code generation logic here
        // }
        function generateQRCode() {
            const qrCodeUrl = "{{asset('admin-das/img/phor-qr.jpg')}}";

            // Configure Toastr to allow HTML content
            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-right",
                timeOut: "25000", // Show for 10 seconds
                escapeHtml: false, // Allow HTML content in the message
            };

            // Display the Toastr notification with the image
            toastr.info(
                `<div style="text-align: center;">
                    <p>Example Screenshot Bakong KHQR</p>
                    <img src="${qrCodeUrl}" alt="Generated QR Code" style="width:250px; height:auto; margin-bottom:10px;border-radius: 8px;">
                    <p>Take a screen shot like this</p>
                    <p>Then uplaod to change Bakong KHQR</p>
                </div>`,
                // "QR Code Generated"
            );
        }

        generateQRCode();
    });
</script>
<script>
    // File Upload
// 
function ekUpload(){
  function Init() {

    console.log("Upload Initialised");

    var fileSelect    = document.getElementById('file-upload'),
        fileDrag      = document.getElementById('file-drag'),
        submitButton  = document.getElementById('submit-button');

    fileSelect.addEventListener('change', fileSelectHandler, false);

    // Is XHR2 available?
    var xhr = new XMLHttpRequest();
    if (xhr.upload) {
      // File Drop
      fileDrag.addEventListener('dragover', fileDragHover, false);
      fileDrag.addEventListener('dragleave', fileDragHover, false);
      fileDrag.addEventListener('drop', fileSelectHandler, false);
    }
  }

  function fileDragHover(e) {
    var fileDrag = document.getElementById('file-drag');

    e.stopPropagation();
    e.preventDefault();

    fileDrag.className = (e.type === 'dragover' ? 'hover' : 'modal-body file-upload');
  }

  function fileSelectHandler(e) {
    // Fetch FileList object
    var files = e.target.files || e.dataTransfer.files;

    // Cancel event and hover styling
    fileDragHover(e);

    // Process all File objects
    for (var i = 0, f; f = files[i]; i++) {
      parseFile(f);
      uploadFile(f);
    }
  }

  // Output
  function output(msg) {
    // Response
    var m = document.getElementById('messages');
    m.innerHTML = msg;
  }

  function parseFile(file) {

    console.log(file.name);
    output(
      '<strong>' + encodeURI(file.name) + '</strong>'
    );
    
    // var fileType = file.type;
    // console.log(fileType);
    var imageName = file.name;

    var isGood = (/\.(?=gif|jpg|png|jpeg)/gi).test(imageName);
    if (isGood) {
      document.getElementById('start').classList.add("hidden");
      document.getElementById('response').classList.remove("hidden");
      document.getElementById('notimage').classList.add("hidden");
      // Thumbnail Preview
      document.getElementById('file-image').classList.remove("hidden");
      document.getElementById('file-image').src = URL.createObjectURL(file);
    }
    else {
      document.getElementById('file-image').classList.add("hidden");
      document.getElementById('notimage').classList.remove("hidden");
      document.getElementById('start').classList.remove("hidden");
      document.getElementById('response').classList.add("hidden");
      document.getElementById("file-upload-form").reset();
    }
  }

  function setProgressMaxValue(e) {
    var pBar = document.getElementById('file-progress');

    if (e.lengthComputable) {
      pBar.max = e.total;
    }
  }

  function updateFileProgress(e) {
    var pBar = document.getElementById('file-progress');

    if (e.lengthComputable) {
      pBar.value = e.loaded;
    }
  }

  function uploadFile(file) {

    var xhr = new XMLHttpRequest(),
      fileInput = document.getElementById('class-roster-file'),
      pBar = document.getElementById('file-progress'),
      fileSizeLimit = 1024; // In MB
    if (xhr.upload) {
      // Check if file is less than x MB
      if (file.size <= fileSizeLimit * 1024 * 1024) {
        // Progress bar
        pBar.style.display = 'inline';
        xhr.upload.addEventListener('loadstart', setProgressMaxValue, false);
        xhr.upload.addEventListener('progress', updateFileProgress, false);

        // File received / failed
        xhr.onreadystatechange = function(e) {
          if (xhr.readyState == 4) {
            // Everything is good!

            // progress.className = (xhr.status == 200 ? "success" : "failure");
            // document.location.reload(true);
          }
        };

        // Start upload
        xhr.open('POST', document.getElementById('file-upload-form').action, true);
        xhr.setRequestHeader('X-File-Name', file.name);
        xhr.setRequestHeader('X-File-Size', file.size);
        xhr.setRequestHeader('Content-Type', 'multipart/form-data');
        xhr.send(file);
      } else {
        output('Please upload a smaller file (< ' + fileSizeLimit + ' MB).');
      }
    }
  }

  // Check for the various File API support.
  if (window.File && window.FileList && window.FileReader) {
    Init();
  } else {
    document.getElementById('file-drag').style.display = 'none';
  }
}
ekUpload();
</script>
@endpush







