<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\Sponsor;

class SponsorComposer
{
    public function compose(View $view)
    {
        $footerSponsors = Sponsor::active()
                                ->position('footer')
                                ->orderBy('sort_order')
                                ->get();

        $view->with('footerSponsors', $footerSponsors);
    }
}
