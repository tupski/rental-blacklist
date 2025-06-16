@if($topups->hasPages())
<div class="row">
    <div class="col-sm-12 col-md-5">
        <div class="dataTables_info text-dark font-weight-medium">
            Menampilkan {{ $topups->firstItem() }} sampai {{ $topups->lastItem() }}
            dari {{ $topups->total() }} data
        </div>
    </div>
    <div class="col-sm-12 col-md-7">
        <nav>
            <ul class="pagination justify-content-end">
                @if ($topups->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link"><i class="fas fa-chevron-left"></i> Sebelumnya</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="#" data-page="{{ $topups->currentPage() - 1 }}">
                            <i class="fas fa-chevron-left"></i> Sebelumnya
                        </a>
                    </li>
                @endif

                @php
                    $start = max(1, $topups->currentPage() - 2);
                    $end = min($topups->lastPage(), $topups->currentPage() + 2);
                @endphp

                @if($start > 1)
                    <li class="page-item">
                        <a class="page-link" href="#" data-page="1">1</a>
                    </li>
                    @if($start > 2)
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    @endif
                @endif

                @for ($i = $start; $i <= $end; $i++)
                    @if ($i == $topups->currentPage())
                        <li class="page-item active">
                            <span class="page-link">{{ $i }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="#" data-page="{{ $i }}">{{ $i }}</a>
                        </li>
                    @endif
                @endfor

                @if($end < $topups->lastPage())
                    @if($end < $topups->lastPage() - 1)
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    @endif
                    <li class="page-item">
                        <a class="page-link" href="#" data-page="{{ $topups->lastPage() }}">{{ $topups->lastPage() }}</a>
                    </li>
                @endif

                @if ($topups->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="#" data-page="{{ $topups->currentPage() + 1 }}">
                            Selanjutnya <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link">Selanjutnya <i class="fas fa-chevron-right"></i></span>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</div>
@endif

<script>
// Handle pagination clicks
$(document).on('click', '.pagination a[data-page]', function(e) {
    e.preventDefault();
    const page = $(this).data('page');
    if (page && typeof loadData === 'function') {
        loadData(page);
    }
});
</script>
