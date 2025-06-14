@if($users->hasPages())
<div class="row">
    <div class="col-sm-12 col-md-5">
        <div class="dataTables_info text-dark font-weight-medium">
            Menampilkan {{ $users->firstItem() }} sampai {{ $users->lastItem() }}
            dari {{ $users->total() }} data
        </div>
    </div>
    <div class="col-sm-12 col-md-7">
        <nav>
            <ul class="pagination justify-content-end">
                @if ($users->onFirstPage())
                    <li class="page-item disabled">
                        <span class="page-link"><i class="fas fa-chevron-left"></i> Sebelumnya</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="#" data-page="{{ $users->currentPage() - 1 }}">
                            <i class="fas fa-chevron-left"></i> Sebelumnya
                        </a>
                    </li>
                @endif

                @php
                    $start = max(1, $users->currentPage() - 2);
                    $end = min($users->lastPage(), $users->currentPage() + 2);
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
                    @if ($i == $users->currentPage())
                        <li class="page-item active">
                            <span class="page-link">{{ $i }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="#" data-page="{{ $i }}">{{ $i }}</a>
                        </li>
                    @endif
                @endfor

                @if($end < $users->lastPage())
                    @if($end < $users->lastPage() - 1)
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    @endif
                    <li class="page-item">
                        <a class="page-link" href="#" data-page="{{ $users->lastPage() }}">{{ $users->lastPage() }}</a>
                    </li>
                @endif

                @if ($users->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="#" data-page="{{ $users->currentPage() + 1 }}">
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
