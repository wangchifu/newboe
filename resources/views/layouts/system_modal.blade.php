<?php 
    if (!session('user_read_ids')) {
      $user_read_ids = \App\Models\UserRead::where('user_id',auth()->user()->id)->pluck('system_post_id')->toArray();    
      session(['user_read_ids' => $user_read_ids]);
    }
    
    $system_posts = [];
    if(session('user_all_read') != 1){      
      $system_posts = \App\Models\SystemPost::whereNotin('id',session('user_read_ids'))
      ->where('start_date','<=',date('Y-m-d'))
      ->where('end_date','>=',date('Y-m-d'))
      ->get();
      if(count($system_posts)==0){
        session(['user_all_read' => 1]);
      }
    }

  ?>
  @if(count($system_posts)>0)
    <script type="text/javascript">
      window.onload = function () {
        $("#simpleModal").modal('show');
      };      
    </script>
    <!-- Modal -->
    <div class="modal fade" id="simpleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">系統公告</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <?php $no_read_sp=''; ?>
          <div class="modal-body">
            <?php $i=1; ?>
            @foreach($system_posts as $system_post)   
            <?php              
              $no_read_sp .= $system_post->id.',';
            ?>         
            編號 {{ $system_post->id }}：<br>
            {!! nl2br($system_post->content) !!}
            @if(count($system_posts)>1 and $i != count($system_posts))
            <hr>
            @endif
            <?php $i++; ?>            
            @endforeach            
            <?php $no_read_sp = substr($no_read_sp,0,-1); ?>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="window.location='{{ route('user_reads',$no_read_sp) }}'">知道了</button>            
          </div>
        </div>
      </div>
    </div>  
  @endif