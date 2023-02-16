<div class="tags-list pt-2 font-weight-bold">
    @if($media->tag_1)
        <span class="media-tag btn btn-xs btn-rounded btn-outline-secondary">{{$media->tag_1}}</span>
    @endif

    @if($media->tag_2)
        <span class="media-tag btn btn-xs btn-rounded btn-outline-secondary">{{$media->tag_2}}</span>
    @endif
    @if($media->tag_3)
        <span class="media-tag btn btn-xs btn-rounded btn-outline-secondary">{{$media->tag_3}}</span>
    @endif

</div>