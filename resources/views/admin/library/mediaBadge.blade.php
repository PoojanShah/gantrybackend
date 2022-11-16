<span class="media-badge text-white text-capitalize">
        @if($media->image)
        <i class="gg gg-image text-white"></i> <span>IMAGE</span>
        @elseif($media->video)
                <i class="gg gg-camera text-white"></i> <span>VIDEO</span>
        @endif
</span>