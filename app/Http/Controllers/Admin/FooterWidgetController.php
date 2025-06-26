<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FooterWidget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FooterWidgetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $widgets = FooterWidget::ordered()->get();
        return view('admin.footer-widgets.index', compact('widgets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = FooterWidget::getTypes();
        return view('admin.footer-widgets.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = $this->validateWidget($request);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only(['title', 'content', 'type', 'order', 'is_active', 'css_class']);
        $data['data'] = $this->processWidgetData($request);

        FooterWidget::create($data);

        return redirect()->route('admin.footer-widgets.index')
            ->with('success', 'Widget footer berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(FooterWidget $footerWidget)
    {
        return view('admin.footer-widgets.show', compact('footerWidget'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FooterWidget $footerWidget)
    {
        $types = FooterWidget::getTypes();
        return view('admin.footer-widgets.edit', compact('footerWidget', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FooterWidget $footerWidget)
    {
        $validator = $this->validateWidget($request, $footerWidget->id);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only(['title', 'content', 'type', 'order', 'is_active', 'css_class']);
        $data['data'] = $this->processWidgetData($request);

        $footerWidget->update($data);

        return redirect()->route('admin.footer-widgets.index')
            ->with('success', 'Widget footer berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FooterWidget $footerWidget)
    {
        $footerWidget->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Widget footer berhasil dihapus'
            ]);
        }

        return redirect()->route('admin.footer-widgets.index')
            ->with('success', 'Widget footer berhasil dihapus');
    }

    /**
     * Update widget order via AJAX
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'widgets' => 'required|array',
            'widgets.*.id' => 'required|exists:footer_widgets,id',
            'widgets.*.order' => 'required|integer'
        ]);

        foreach ($request->widgets as $widget) {
            FooterWidget::where('id', $widget['id'])
                ->update(['order' => $widget['order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Urutan widget berhasil diperbarui'
        ]);
    }

    /**
     * Toggle widget status
     */
    public function toggleStatus(FooterWidget $footerWidget)
    {
        $footerWidget->update([
            'is_active' => !$footerWidget->is_active
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status widget berhasil diubah',
            'is_active' => $footerWidget->is_active
        ]);
    }

    /**
     * Validate widget data
     */
    private function validateWidget(Request $request, $id = null)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'type' => 'required|in:' . implode(',', array_keys(FooterWidget::getTypes())),
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'css_class' => 'nullable|string|max:255'
        ];

        // Add specific validation based on type
        if ($request->type === 'links') {
            $rules['links'] = 'required|array|min:1';
            $rules['links.*.text'] = 'required|string|max:255';
            $rules['links.*.url'] = 'required|url|max:500';
        } elseif ($request->type === 'contact') {
            $rules['contact_address'] = 'nullable|string|max:500';
            $rules['contact_phone'] = 'nullable|string|max:20';
            $rules['contact_email'] = 'nullable|email|max:255';
            $rules['contact_whatsapp'] = 'nullable|string|max:20';
        } elseif ($request->type === 'social') {
            $rules['social'] = 'required|array|min:1';
            $rules['social.*.platform'] = 'required|string|max:50';
            $rules['social.*.url'] = 'required|url|max:500';
        }

        return Validator::make($request->all(), $rules);
    }

    /**
     * Process widget data based on type
     */
    private function processWidgetData(Request $request)
    {
        $data = [];

        switch ($request->type) {
            case 'links':
                if ($request->has('links')) {
                    $data['links'] = array_filter($request->links, function($link) {
                        return !empty($link['text']) && !empty($link['url']);
                    });
                }
                break;

            case 'contact':
                $contactFields = ['address', 'phone', 'email', 'whatsapp'];
                foreach ($contactFields as $field) {
                    $value = $request->input('contact_' . $field);
                    if (!empty($value)) {
                        $data[$field] = $value;
                    }
                }
                break;

            case 'social':
                if ($request->has('social')) {
                    $data['social'] = array_filter($request->social, function($social) {
                        return !empty($social['platform']) && !empty($social['url']);
                    });
                }
                break;
        }

        return $data;
    }
}
