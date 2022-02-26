<p align="center"><a href="https://quantizd.com/transcoding-videos-using-ffmpeg-and-laravel-queues/" target="_blank"><img src="https://quantizd.com/wp-content/uploads/2018/09/laravel-ffmpeg-768x504.png" width="800"></a></p>


# Transcoding Videos for Web Streaming with FFmpeg using Laravel Queues

## Content
<span id="content"></span>
<ul>
<li><a href="#create_video"> Create Video Model </a></li>
<li><a href="#route"> Add the Routes </a></li>
<li><a href="#controller"> Add Controller Methods</a></li>
<li><a href="#view"> Create View(Blade) Files</a></li>
<li><a href="#form_req"> Create a Form Request</a></li>
<li><a href="#job"> Converter Job </a></li>
<li><a href="#ffmpeg"> Install FFMPEG Package </a></li>
<li><a href="#queue"> Handle Queue</a></li>
<li><a href="#video"> Display Video </a></li>
<li><a href="#final"> Finale </a></li>
<li><a href="https://quantizd.com/transcoding-videos-using-ffmpeg-and-laravel-queues/"> Tutorial Link </a></li>
</ul>


<br><br>

<p><b> I’ve been working on a project where we were using AWS elastic transcoder for media conversion. Elastic transcoder is a highly scalable solution for media transcoding. However, it charges your per minute for media conversion depending on your region. To reduce operational costs, we decided to shift away from AWS transcoder and use FFmpeg with Laravel for media conversion on our own servers. In this tutorial, I’ll show you how we can use FFmpeg for media conversion and defer processing using Laravel Queues.</b></p>


<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>







## <span id="create_video">Create Video Model, Controller, Migration</span>

<br>

Let’s get started by setting up a new project. Create a new Video model, its migration, and controller.  We will store uploaded videos information on videos table.

```SH
    php artisan make:model Video --migration --controller
```

```PHP
    class CreateVideosTable extends Migration
    {
        /**
        * Run the migrations.
        *
        * @return void
        */
        public function up()
        {
            Schema::create('videos', function (Blueprint $table) {
                $table->increments('id');
                $table->string('title');
                $table->string('original_name');
                $table->string('disk');
                $table->string('path');
                $table->string('stream_path')->nullable();
                $table->boolean('processed')->default(false);
                $table->datetime('converted_for_streaming_at')->nullable();
                $table->timestamps();
            });
        }
    
        /**
        * Reverse the migrations.
        *
        * @return void
        */
        public function down()
        {
            Schema::dropIfExists('videos');
        }
    }
```
<br>


On file upload, we will store video **title**, **original file name**, and **path of the stored file** in the database. After upload, we will dispatch a Job for transcoding it to a web streamable format and update **stream_path** with the output file path, update **converted_for_streaming_at** timestamp and set **processed** to true after FFmpeg is done processing uploaded media file.

<br>


```PHP
    class Video extends Model
    {
        protected $dates = [
            'converted_for_streaming_at',
        ];

        protected $guarded = [];
    }
```


In Video model class, add the <b>converted_for_streaming_at</b> column to ``$dates`` array so that it should be mutated to dates like <b>created_at</b> or <b>updated_at</b> columns.

<a href="#content"> Back To Content </a>

<br><br>


## <span id="route">Add the Routes</span>

Add these routes to ``web.php`` file.

```PHP
    Route::group(['middleware' => ['auth']], function(){

        Route::get('/', 'VideoController@index');

        Route::get('/uploader', 'VideoController@uploader')->name('uploader');

        Route::post('/upload', 'VideoController@store')->name('upload');
    });
```

<br>

``GET /uploader`` route will render a form for uploading videos and ``POST /upload`` route will handle the form submission, upload video, create a database record and dispatch an FFmpeg transcoding job. ``GET / index`` route will render videos view where all uploaded videos will be displayed in native HTML video player.


<a href="#content"> Back To Content </a>

<br><br>



## <span id="controller">Add Controller Methods</span>

In ``VideoController`` add these methods.

```PHP
    class VideoController extends Controller
    {

        /**
         * Return video blade view and pass videos to it.
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
         */
        public function index()
        {
            $videos = Video::orderBy('created_at', 'DESC')->get();
            return view('videos')->with('videos', $videos);
        }

        /**
         * Return uploader form view for uploading videos
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
         */
        public function uploader(){
            return view('uploader');
        }

        /**
         * Handles form submission after uploader form submits
         * @param StoreVideoRequest $request
         * @return \Illuminate\Http\RedirectResponse
         */
        public function store(StoreVideoRequest $request)
        {
            $path = str_random(16) . '.' . $request->video->getClientOriginalExtension();
            $request->video->storeAs('public', $path);

            $video = Video::create([
                'disk'          => 'public',
                'original_name' => $request->video->getClientOriginalName(),
                'path'          => $path,
                'title'         => $request->title,
            ]);

            ConvertVideoForStreaming::dispatch($video);

            return redirect('/uploader')
                ->with(
                    'message',
                    'Your video will be available shortly after we process it'
                );
        }
    }
```


<a href="#content"> Back To Content </a>

<br><br>

## <span id="view">Create View(Blade) Files</span>

Create  uploader.blade.php under views directory.

<br>

```PHP
    @extends('layouts.app')

    @section('content')
        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-6 mr-auto ml-auto mt-5">
            <h3 class="text-center">
                Upload Video
            </h3>
            <form method="post" action="{{ route('upload') }}" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="video-title">Title</label>
                    <input type="text"
                        class="form-control"
                        name="title"
                        placeholder="Enter video title">
                    @if($errors->has('title'))
                        <span class="text-danger">
                            {{$errors->first('title')}}
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="exampleFormControlFile1">Video File</label>
                    <input type="file" class="form-control-file" name="video">

                    @if($errors->has('video'))
                        <span class="text-danger">
                            {{$errors->first('video')}}
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-default">
                </div>

                {{csrf_field()}}
            </form>
        </div>
    @endSection
```

<a href="#content"> Back To Content </a>

<br><br>


## <span id="form_req">Create a Form Request</span>

Also, create a ``StoreVideoRequest`` form request for validating uploader form input.

<br>

```SH
    php artisan make:request StoreVideoRequest
```

```PHP
    class StoreVideoRequest extends FormRequest
    {
        /**
         * Determine if the user is authorized to make this request.
         *
         * @return bool
         */
        public function authorize()
        {
            return true;
        }

        /**
         * Get the validation rules that apply to the request.
         *
         * @return array
         */
        public function rules()
        {
            return [
                'title' => 'required',
                'video' => 'required|file|mimetypes:video/*',
            ];
        }
    }
```

<br>

We have a  **mimetypes** validation rule with ``video/*`` wildcard to only allow video uploads.


<a href="#content"> Back To Content </a>

<br><br>


## <span id="job">Create a Converter Job</span>

Now create a  ``ConvertVideoForStreaming`` job which will be dispatched after video is done uploading and a database record is created in ``VideoController@store``  method.

```SH
    php artisan make:job ConvertVideoForStreaming
```

```PHP
    use FFMpeg;
    use App\Video;
    use Carbon\Carbon;
    use FFMpeg\Coordinate\Dimension;
    use FFMpeg\Format\Video\X264;
    use Illuminate\Bus\Queueable;
    use Illuminate\Queue\SerializesModels;
    use Illuminate\Queue\InteractsWithQueue;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Foundation\Bus\Dispatchable;
    
    class ConvertVideoForStreaming implements ShouldQueue
    {
        use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

        public $video;

        /**
         * Create a new job instance.
         *
         * @param Video $video
         */
        public function __construct(Video $video)
        {
            $this->video = $video;
        }

        /**
         * Execute the job.
         *
         * @return void
         */
        public function handle()
        {
            // create a video format...
            $lowBitrateFormat = (new X264('libmp3lame', 'libx264'))->setKiloBitrate(500);

            $converted_name = $this->getCleanFileName($this->video->path);

            // open the uploaded video from the right disk...
            FFMpeg::fromDisk($this->video->disk)
                ->open($this->video->path)

                // add the 'resize' filter...
                ->addFilter(function ($filters) {
                    $filters->resize(new Dimension(960, 540));
                })

                // call the 'export' method...
                ->export()

                // tell the MediaExporter to which disk and in which format we want to export...
                ->toDisk('public')
                ->inFormat($lowBitrateFormat)

                // call the 'save' method with a filename...
                ->save($converted_name);

            // update the database so we know the convertion is done!
            $this->video->update([
                'converted_for_streaming_at' => Carbon::now(),
                'processed' => true,
                'stream_path' => $converted_name
            ]);
        }

        private function getCleanFileName($filename){
            return preg_replace('/\\.[^.\\s]{3,4}$/', '', $filename) . '.mp4';
        }
    }
```

<br>

In ``handle()`` method of the dispatched job, we will create a low bitrate X264 format. 
We will open uploaded file from **public disk** and add a **resize** filter to it. 
Then we will tell **FFmpeg** to start transcoding by calling ``export()`` method and output file to public disk in a low bitrate mp4 container format.


<a href="#content"> Back To Content </a>

<br><br>


## <span id="ffmpeg">Install FFMPEG Package </span>

Before you go an test it, make sure you have installed **Laravel FFmpeg** package that we are using in our transcoding job.

<br>


```SH
    composer require pbmedia/laravel-ffmpeg
```

<br>

Also, make sure you have **ffmpeg binaries** installed of your machine. 
If you’re running Linux, you can easily install it by running following **apt install** command.

<br>

```SH
    sudo apt-get install ffmpeg
```

<br>

You must also add **FFmpeg Service Provider** and **Facade** to ``app.php``.

<br>

```PHP
    'providers' => [
        ...
        Pbmedia\LaravelFFMpeg\FFMpegServiceProvider::class,
        ...
    ];

    'aliases' => [
        ...
        'FFMpeg' => Pbmedia\LaravelFFMpeg\FFMpegFacade::class
        ...
    ];
```

<br>

and run following command to **publish** package configuration files.

<br>

```SH
    php artisan vendor:publish --provider="Pbmedia\LaravelFFMpeg\FFMpegServiceProvider"
```

<br>

If you’re running windows, you must *add ffmpeg binaries to the system* **PATH**. 
If you don’t have access to that, you can define these environment variables in your .env file.


```ENV
    FFMPEG_BINARIES='PATH_TO_FFMPEG_BINARUES'
    FFPROBE_BINARIES='PATH_TO_FFPROBE_BINARIES'
```



<a href="#content"> Back To Content </a>


<br>


## <span id="queue">Handle Queues</span>

<br>
<br>

### Laravel Queues Configuration

<br>

You also need to configure queue connection in your **env** file. 
For this tutorial, I’m using database queue connection. 
Edit ``.env``  file and update **QUEUE_CONNECTION** variable to database.

<br>

Also run  ``php artisan queue:table`` to create database queue table migration and php artisan migrate to create table. 
To deal with failed jobs, ``run  php artisan queue:failed-table`` to create failed queue jobs migration table and  ``php artisan migrate`` to create table.


<br>


### Running Queue Worker

<br>

Before we go and test, run Laravel’s queue worker

<br>

```SH
    php artisan queue:work --tries=3 --timeout=8600
```
	
<br>

we have added a ``--timeout`` flag to queue worker. This indicates that don’t want our queue jobs to run longer than **8600 seconds**.

Now if you head over to ``/uploader`` route in your application and upload a video file, a database record will be created a transcoding job will be dispatched. You’ll be able to view your dispatched job in the terminal.


<a href="#content"> Back To Content </a>

<br><br>


## <span id="video"> Display Videos </span>

Create ``videos.blade.php`` file under view directory.

<br>


```PHP
    @extends('layouts.app')

    @section('content')
        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 mr-auto ml-auto mt-5">
            <h3 class="text-center">
                Videos
            </h3>
            
            @foreach($videos as $video)
                <div class="row mt-5">
                    <div class="video" >
                        <div class="title">
                            <h4>
                                {{$video->title}}
                            </h4>
                        </div>
                        @if($video->processed)
                            <video src="/storage/{{$video->stream_path}}"
                                class="w-100"
                                controls></video>
                        @else
                            <div class="alert alert-info w-100">
                                Video is currently being processed and will be available shortly
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endSection
```

<br>

We will display an alert for videos that are currently being processed. 
For processed videos, we will render a video element with transcoded stream_path.


<a href="#content"> Back To Content </a>

<br><br>


## <span id="final"> Final Touches </span>



[Here’s](https://youtu.be/OUquEypEgEA) a *demo* of what we have done so far.


I have set up a [Github repository](https://github.com/waleedahmad/laravel-stream) with example application code. 
If you run into any issue or have any questions, leave a comment and I will try to help you in any way possible.




<br>
<br>

<hr>

Tutorial [LINK](https://quantizd.com/transcoding-videos-using-ffmpeg-and-laravel-queues/)
