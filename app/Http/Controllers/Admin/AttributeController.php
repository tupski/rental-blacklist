<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'jenis_rental');
        $types = Attribute::getTypes();

        $attributes = Attribute::ofType($type)
                              ->ordered()
                              ->paginate(20);

        return view('admin.attributes.index', compact('attributes', 'types', 'type'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $type = $request->get('type', 'jenis_rental');
        $types = Attribute::getTypes();

        return view('admin.attributes.create', compact('types', 'type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:' . implode(',', array_keys(Attribute::getTypes())),
            'name' => 'required|string|max:255',
            'value' => 'required|string|max:255|unique:attributes,value,NULL,id,type,' . $request->type,
            'description' => 'nullable|string',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        $data['is_default'] = $request->has('is_default');

        // If this is set as default, remove default from others of same type
        if ($data['is_default']) {
            Attribute::ofType($data['type'])->update(['is_default' => false]);
        }

        Attribute::create($data);

        return redirect()->route('admin.atribut.indeks', ['type' => $data['type']])
                        ->with('success', 'Atribut berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Attribute $attribute)
    {
        return view('admin.attributes.show', compact('attribute'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attribute $attribute)
    {
        $types = Attribute::getTypes();
        return view('admin.attributes.edit', compact('attribute', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attribute $attribute)
    {
        $request->validate([
            'type' => 'required|string|in:' . implode(',', array_keys(Attribute::getTypes())),
            'name' => 'required|string|max:255',
            'value' => 'required|string|max:255|unique:attributes,value,' . $attribute->id . ',id,type,' . $request->type,
            'description' => 'nullable|string',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');
        $data['is_default'] = $request->has('is_default');

        // If this is set as default, remove default from others of same type
        if ($data['is_default']) {
            Attribute::ofType($data['type'])
                     ->where('id', '!=', $attribute->id)
                     ->update(['is_default' => false]);
        }

        $attribute->update($data);

        return redirect()->route('admin.atribut.indeks', ['type' => $data['type']])
                        ->with('success', 'Atribut berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attribute $attribute)
    {
        $type = $attribute->type;
        $attribute->delete();

        return redirect()->route('admin.atribut.indeks', ['type' => $type])
                        ->with('success', 'Atribut berhasil dihapus.');
    }

    /**
     * Toggle attribute status
     */
    public function toggleStatus(Attribute $attribute)
    {
        $attribute->update(['is_active' => !$attribute->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Status atribut berhasil diubah.',
            'is_active' => $attribute->is_active
        ]);
    }

    /**
     * Update attribute order
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'attributes' => 'required|array',
            'attributes.*.id' => 'required|exists:attributes,id',
            'attributes.*.order' => 'required|integer|min:0',
        ]);

        foreach ($request->attributes as $attrData) {
            Attribute::where('id', $attrData['id'])
                     ->update(['order' => $attrData['order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Urutan atribut berhasil diperbarui.'
        ]);
    }
}
