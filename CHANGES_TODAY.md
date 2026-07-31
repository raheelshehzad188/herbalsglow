# Changes Made Today - Featured Categories with Sort Field

## Date: 2025-01-20

## Summary
1. Added sort field to categories for home page sorting
2. Implemented sidebar menu with subcategories functionality
3. Replaced Summernote with CKEditor in product form with image upload functionality

---

## 1. Database Migration

### File: `database/migrations/2025_01_20_000000_add_sort_to_categories_table.php`
**Action:** Create new migration file

**SQL Query to Run:**
```sql
ALTER TABLE `categories` ADD COLUMN `sort` INT(11) DEFAULT 0 AFTER `show_on_home`;
```

**Or run migration:**
```bash
php artisan migrate
```

---

## 2. Category Controller Updates

### File: `app/Http/Controllers/Admins/AdminController.php`

**Location:** `category()` method (around line 300-415)

**Changes:**
1. **Update Section (around line 325):**
   - Added: `$category->sort=isset($request->sort) ? $request->sort : 0;`

2. **Create Section (around line 375):**
   - Added: `$category->sort=isset($request->sort) ? $request->sort : 0;`

**Code to Add:**

In the UPDATE section (after line 324):
```php
$category->sort=isset($request->sort) ? $request->sort : 0;
```

In the CREATE section (after line 374):
```php
$category->sort=isset($request->sort) ? $request->sort : 0;
```

---

## 3. Category Admin Form

### File: `resources/views/admins/category.blade.php`

**Location:** After the status dropdown (around line 86-91)

**Code to Add:**
```blade
<div class="form-group" style="display: flex;flex-direction: column;margin-bottom: 8px;">
    <label>Sort Order:</label>
    <input type="number" name="sort" class="form-control" value="<?php echo isset($edit->sort) ? $edit->sort : 0; ?>" min="0" required>
    <small class="text-muted">Lower numbers appear first on home page</small>
</div>
```

**Add this after:**
```blade
<div class="form-group" style="display: flex;flex-direction: column;margin-bottom: 8px;">
    <select name="status" class="form-control" required>
        <option value="1" <?=  (isset($edit->status) && $edit->status == 1)?'selected':''; ?>>Active</option>
        <option value="0" <?=  (isset($edit->status) && $edit->status == 0)?'selected':''; ?> >Inactive</option>
    </select>
</div>
```

---

## 4. FrontController Updates

### File: `app/Http/Controllers/Front/FrontController.php`

**Location:** Featured categories query (around line 354-358 and 488-492)

**Find:**
```php
// Fetch featured categories with their products
$featured_categories = Category::where('status', 1)
    ->where('show_on_home', 1)
    ->orderBy('id', 'DESC')
    ->get();
```

**Replace with:**
```php
// Fetch featured categories with their products (only active categories, sorted by sort field)
$featured_categories = Category::where('status', 1)
    ->where('show_on_home', 1)
    ->orderBy('sort', 'ASC')
    ->orderBy('id', 'DESC')
    ->get();
```

**Note:** This appears in both `home()` and `home1()` methods.

---

## 5. Header File Updates

### File: `resources/views/theme2/header.blade.php`

**Location 1: Top of file (around line 3-14)**

**Find:**
```php
$cate = DB::table('categories')->get();
```

**Replace with:**
```php
$cate = DB::table('categories')->where('status', 1)->orderBy('sort', 'ASC')->orderBy('id', 'DESC')->get();
// Load subcategories for each category
foreach($cate as $k => $v) {
    $cate[$k]->subcategories = DB::table('sub_categories')->where('category_id', $v->id)->get();
}
```

**Location 2: Sidebar section (around line 189-211)**

**Find:**
```blade
<ul>
    @foreach($cate as $k=> $v)
    <li class="has-submenu">
        <a href="{{ url('/')}}/{{$v->slug}}">{{$v->name}}</a>
        <i class="fa-solid fa-caret-down toggle-submenu"></i>
        @if(isset($v->subcategories) && count($v->subcategories) > 0)
        <ul class="submenu">
            @foreach($v->subcategories as $sub)
            <li><a href="{{ url('/')}}/{{$sub->slug}}">{{$sub->name}}</a></li>
            @endforeach
        </ul>
        @endif
    </li>
    @endforeach
</ul>
```

**Replace with:**
```blade
<ul>
    @foreach($cate as $k=> $v)
    <li class="has-submenu {{ (isset($v->subcategories) && count($v->subcategories) > 0) ? 'has-children' : '' }}">
        <div class="category-item">
            <a href="{{ url('/')}}/{{$v->slug}}" class="category-link">{{$v->name}}</a>
            @if(isset($v->subcategories) && count($v->subcategories) > 0)
            <i class="fa-solid fa-caret-down toggle-submenu"></i>
            @endif
        </div>
        @if(isset($v->subcategories) && count($v->subcategories) > 0)
        <ul class="submenu">
            @foreach($v->subcategories as $sub)
            <li><a href="{{ url('/')}}/{{$sub->slug}}">{{$sub->name}}</a></li>
            @endforeach
        </ul>
        @endif
    </li>
    @endforeach
</ul>
```

---

## 6. JavaScript Updates

### File: `public/theme2/js/ayanstore.js`

**Location:** Around line 222-240

**Find:**
```javascript
document.addEventListener("DOMContentLoaded", function () {
    const toggles = document.querySelectorAll(".toggle-submenu");

    // ✅ Run only if sidebar submenu toggles exist
    if (toggles.length > 0) {
        toggles.forEach(toggle => {
            toggle.addEventListener("click", function () {
                const submenu = this.nextElementSibling;

                if (submenu && submenu.classList.contains("submenu")) {
                    submenu.classList.toggle("active");
                }

                // rotate arrow
                this.classList.toggle("open");
            });
        });
    }
});
```

**Replace with:**
```javascript
document.addEventListener("DOMContentLoaded", function () {
    // Function to toggle submenu
    function toggleSubmenu(toggleElement) {
        const categoryItem = toggleElement.closest('.has-submenu');
        if (!categoryItem) return;
        
        const submenu = categoryItem.querySelector('.submenu');
        const toggleIcon = categoryItem.querySelector('.toggle-submenu');
        
        if (submenu) {
            submenu.classList.toggle("active");
        }
        
        if (toggleIcon) {
            toggleIcon.classList.toggle("open");
        }
    }

    // Handle click on toggle icon
    const toggles = document.querySelectorAll(".toggle-submenu");
    if (toggles.length > 0) {
        toggles.forEach(toggle => {
            toggle.addEventListener("click", function (e) {
                e.preventDefault();
                e.stopPropagation();
                toggleSubmenu(this);
            });
        });
    }

    // Handle click on category link (only if it has subcategories)
    const categoryLinks = document.querySelectorAll(".has-submenu.has-children .category-link");
    if (categoryLinks.length > 0) {
        categoryLinks.forEach(link => {
            link.addEventListener("click", function (e) {
                e.preventDefault();
                const categoryItem = this.closest('.has-submenu');
                const toggleIcon = categoryItem.querySelector('.toggle-submenu');
                if (toggleIcon) {
                    toggleSubmenu(toggleIcon);
                }
            });
        });
    }
});
```

---

## 7. CSS Updates

### File: `public/theme2/css/stylesheet.css`

**Location:** Around line 379-403

**Find:**
```css
.sidebar ul li {
	padding: 15px 25px;
	font-weight: 600;
	border-top: 1px solid #eee;
}
.sidebar ul li a{
	font-weight: 600;
	font-size: 14px;
	text-decoration: none;
	color:#343a40;
}
.sidebar ul li a:hover{
	color:#000;
	text-decoration: underline;
	font-weight: 700;
}
.sidebar ul li i:hover{
	color:#000;
}
.sidebar ul li i{
	font-size: 10px;
    color: #343a40;
    padding: 5px 10px;
    cursor: pointer;
}
```

**Replace with:**
```css
.sidebar ul li {
	padding: 0;
	font-weight: 600;
	border-top: 1px solid #eee;
}
.sidebar ul li .category-item {
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding: 15px 25px;
}
.sidebar ul li .category-link {
	font-weight: 600;
	font-size: 14px;
	text-decoration: none;
	color:#343a40;
	flex: 1;
}
.sidebar ul li .category-link:hover {
	color:#000;
	text-decoration: underline;
	font-weight: 700;
}
.sidebar ul li i:hover{
	color:#000;
}
.sidebar ul li i{
	font-size: 10px;
    color: #343a40;
    padding: 5px 10px;
    cursor: pointer;
}
.sidebar ul li.has-children .category-link {
	cursor: pointer;
}
```

**Location 2:** Around line 447-454

**Find:**
```css
.submenu {
	display: none;
	width: 100%; 
	background: #fff;
	padding: 0 !important;
	margin: 0;
	border-top: 1px solid #eee;
}
```

**Replace with:**
```css
.submenu {
	display: none;
	width: 100%; 
	background: #f8f9fa;
	padding: 0 !important;
	margin: 0;
	border-top: 1px solid #eee;
}
.submenu.active {
	display: block;
}
```

---

## 8. CKEditor Implementation for Product Form

### File: `routes/web.php`

**Location:** After product routes (around line 108-109)

**Code to Add:**
```php
Route::post('/ckeditor/upload',[Admins\AdminController::class,'ckeditor_upload'])->name('ckeditor_upload');
```

**Add this after:**
```php
Route::get('/product/delete/{id}',[Admins\AdminController::class,'product_delete'])->name('product_delete');
```

---

### File: `app/Http/Controllers/Admins/AdminController.php`

**Location:** End of class (around line 2324)

**Code to Add:**
```php
public function ckeditor_upload(Request $request)
{
    if ($request->hasFile('upload')) {
        $file = $request->file('upload');
        $extension = $file->getClientOriginalExtension();
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (!in_array(strtolower($extension), $allowedExtensions)) {
            return response()->json([
                'error' => [
                    'message' => 'Invalid file type. Only images are allowed.'
                ]
            ], 400);
        }
        
        $filename = time() . '_' . uniqid() . '.' . $extension;
        $file->move(public_path('uploads/ckeditor'), $filename);
        
        $url = url('/') . '/uploads/ckeditor/' . $filename;
        
        return response()->json([
            'url' => $url
        ]);
    }
    
    return response()->json([
        'error' => [
            'message' => 'No file uploaded.'
        ]
    ], 400);
}
```

---

### File: `resources/views/admins/product_form.blade.php`

**Location 1: Textarea fields (around line 232-268)**

**Find:**
```blade
<textarea class="summernote" name="short_discriiption" id="short_discriiption" style="height:500px">
<textarea class="summernote" name="product_details" id="product_details" rows="15">
<textarea class="summernote" name="add_info" id="add_info" rows="15">
```

**Replace with:**
```blade
<textarea name="short_discriiption" id="short_discriiption">
<textarea name="product_details" id="product_details">
<textarea name="add_info" id="add_info">
```

**Location 2: JavaScript section (around line 467-488)**

**Find:**
```javascript
$(document).ready(function() {
    $(document).on("submit","#product_form",function(e){
        $("#short_discriiption").val($('#short_discriiption').summernote('code'));
        $("#product_details").val($('#product_details').summernote('code'));
        return true;
    });

    $(document).on("submit","#product_form",function(e){
        if ($('#product_details').summernote('codeview.isActivated')) {
            $('#product_details').summernote('codeview.deactivate'); 
        }
        if ($('#short_discriiption').summernote('codeview.isActivated')) {
            $('#short_discriiption').summernote('codeview.deactivate'); 
        }
    });
});
```

**Replace with:**
```javascript
// Custom Upload Adapter for CKEditor 5
class MyUploadAdapter {
    constructor(loader) {
        this.loader = loader;
    }

    upload() {
        return this.loader.file
            .then(file => new Promise((resolve, reject) => {
                this._initRequest();
                this._initListeners(resolve, reject, file);
                this._sendRequest(file);
            }));
    }

    abort() {
        if (this.xhr) {
            this.xhr.abort();
        }
    }

    _initRequest() {
        const xhr = this.xhr = new XMLHttpRequest();
        xhr.open('POST', '{{ route("admins.ckeditor_upload") }}', true);
        xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
    }

    _initListeners(resolve, reject, file) {
        const xhr = this.xhr;
        const loader = this.loader;
        const genericErrorText = `Couldn't upload file: ${file.name}.`;

        xhr.addEventListener('error', () => reject(new Error(genericErrorText)));
        xhr.addEventListener('abort', () => reject());
        xhr.addEventListener('load', () => {
            const response = JSON.parse(xhr.response);

            if (!response || response.error) {
                return reject(response && response.error ? new Error(response.error.message) : new Error(genericErrorText));
            }

            resolve({
                default: response.url
            });
        });

        if (xhr.upload) {
            xhr.upload.addEventListener('progress', evt => {
                if (evt.lengthComputable) {
                    loader.uploadTotal = evt.total;
                    loader.uploaded = evt.loaded;
                }
            });
        }
    }

    _sendRequest(file) {
        const data = new FormData();
        data.append('upload', file);
        this.xhr.send(data);
    }
}

function MyCustomUploadAdapterPlugin(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
        return new MyUploadAdapter(loader);
    };
}

// CKEditor initialization with image upload
ClassicEditor
    .create(document.querySelector('#short_discriiption'), {
        extraPlugins: [MyCustomUploadAdapterPlugin],
        toolbar: {
            items: [
                'heading', '|',
                'bold', 'italic', 'link', '|',
                'bulletedList', 'numberedList', '|',
                'insertTable', 'imageUpload', '|',
                'undo', 'redo'
            ]
        },
        image: {
            toolbar: [
                'imageStyle:full',
                'imageStyle:side',
                '|',
                'imageTextAlternative'
            ]
        }
    })
    .then(editor => {
        window.shortDescriptionEditor = editor;
    })
    .catch(error => {
        console.error('Error initializing short description editor:', error);
    });

ClassicEditor
    .create(document.querySelector('#product_details'), {
        extraPlugins: [MyCustomUploadAdapterPlugin],
        toolbar: {
            items: [
                'heading', '|',
                'bold', 'italic', 'link', '|',
                'bulletedList', 'numberedList', '|',
                'insertTable', 'imageUpload', '|',
                'undo', 'redo'
            ]
        },
        image: {
            toolbar: [
                'imageStyle:full',
                'imageStyle:side',
                '|',
                'imageTextAlternative'
            ]
        }
    })
    .then(editor => {
        window.productDetailsEditor = editor;
    })
    .catch(error => {
        console.error('Error initializing product details editor:', error);
    });

ClassicEditor
    .create(document.querySelector('#add_info'), {
        extraPlugins: [MyCustomUploadAdapterPlugin],
        toolbar: {
            items: [
                'heading', '|',
                'bold', 'italic', 'link', '|',
                'bulletedList', 'numberedList', '|',
                'insertTable', 'imageUpload', '|',
                'undo', 'redo'
            ]
        },
        image: {
            toolbar: [
                'imageStyle:full',
                'imageStyle:side',
                '|',
                'imageTextAlternative'
            ]
        }
    })
    .then(editor => {
        window.addInfoEditor = editor;
    })
    .catch(error => {
        console.error('Error initializing add info editor:', error);
    });

$(document).ready(function() {
    $(document).on("submit","#product_form",function(e){
        // Get CKEditor content and set it to textarea
        if (window.shortDescriptionEditor) {
            document.getElementById('short_discriiption').value = window.shortDescriptionEditor.getData();
        }
        if (window.productDetailsEditor) {
            document.getElementById('product_details').value = window.productDetailsEditor.getData();
        }
        if (window.addInfoEditor) {
            document.getElementById('add_info').value = window.addInfoEditor.getData();
        }
        return true;
    });
});
```

---

### Directory Creation

**Action:** Create upload directory

**Command to Run:**
```bash
mkdir -p public/uploads/ckeditor
```

**Or manually create:** `public/uploads/ckeditor/` folder

---

## Files Modified Summary

1. ✅ `database/migrations/2025_01_20_000000_add_sort_to_categories_table.php` (NEW FILE)
2. ✅ `app/Http/Controllers/Admins/AdminController.php`
3. ✅ `resources/views/admins/category.blade.php`
4. ✅ `app/Http/Controllers/Front/FrontController.php`
5. ✅ `resources/views/theme2/header.blade.php`
6. ✅ `public/theme2/js/ayanstore.js`
7. ✅ `public/theme2/css/stylesheet.css`
8. ✅ `routes/web.php` (NEW ROUTE)
9. ✅ `resources/views/admins/product_form.blade.php`
10. ✅ `public/uploads/ckeditor/` (NEW DIRECTORY - create manually)

---

## Testing Checklist

After applying changes:

1. ✅ Run the SQL migration or `php artisan migrate`
2. ✅ Create `public/uploads/ckeditor/` directory
3. ✅ Check admin panel - Category form should have "Sort Order" field
4. ✅ Set sort order for some categories in admin
5. ✅ Check home page - categories should be sorted by sort field
6. ✅ Click "All Categories" button - sidebar should slide from left
7. ✅ Click on a category with subcategories - subcategories should expand
8. ✅ Click on toggle icon - subcategories should expand/collapse
9. ✅ Go to Product Form - CKEditor should load instead of Summernote
10. ✅ In CKEditor, click image upload button - select image - it should upload to server
11. ✅ After upload, image link should automatically appear in editor
12. ✅ Test in both Create and Edit product modes

---

## Important Notes

### Categories & Sorting:
- The `sort` field defaults to 0, so existing categories will have sort = 0
- Lower sort numbers appear first on home page
- Only active categories (`status = 1`) with `show_on_home = 1` appear on home page
- Subcategories don't have status field, so all subcategories are loaded
- Sidebar menu shows all active categories sorted by sort field

### CKEditor:
- CKEditor 5 is already included in `master.blade.php` (line 758)
- Images are uploaded to `public/uploads/ckeditor/` directory
- Allowed image types: jpg, jpeg, png, gif, webp
- Image upload works in both Create and Edit product modes
- Uploaded images get unique filenames with timestamp and uniqid
- Image URLs are automatically inserted into editor after upload

---

## Quick Reference: Files Changed Today

### Complete File List:

**New Files Created:**
1. `database/migrations/2025_01_20_000000_add_sort_to_categories_table.php`
2. `public/uploads/ckeditor/` (directory - create manually)

**Modified Files:**
1. `app/Http/Controllers/Admins/AdminController.php`
   - Added sort field handling in category() method
   - Added ckeditor_upload() method at end of class

2. `app/Http/Controllers/Front/FrontController.php`
   - Updated featured_categories query in home() and home1() methods

3. `routes/web.php`
   - Added ckeditor_upload route

4. `resources/views/admins/category.blade.php`
   - Added sort order input field

5. `resources/views/admins/product_form.blade.php`
   - Replaced Summernote with CKEditor
   - Added custom upload adapter
   - Updated form submission handler

6. `resources/views/theme2/header.blade.php`
   - Updated categories query to load subcategories
   - Updated sidebar HTML structure

7. `public/theme2/js/ayanstore.js`
   - Updated submenu toggle functionality with event delegation

8. `public/theme2/css/stylesheet.css`
   - Updated sidebar and submenu CSS styles

---

## Folders to Replace (if restoring from backup)

If you need to replace folders from backup, these folders contain today's changes:

1. **app/** - Contains AdminController.php with sort field and ckeditor_upload method
2. **resources/** - Contains:
   - views/admins/category.blade.php (sort field)
   - views/admins/product_form.blade.php (CKEditor)
   - views/theme2/header.blade.php (sidebar with subcategories)
3. **public/** - Contains:
   - theme2/js/ayanstore.js (sidebar JavaScript)
   - theme2/css/stylesheet.css (sidebar CSS)
   - uploads/ckeditor/ (new directory for uploaded images - create manually)
4. **routes/** - Contains web.php with ckeditor_upload route
5. **database/migrations/** - Contains migration file for sort field

---

## Rollback Instructions (if needed)

To remove these changes:

1. Remove `sort` column from categories table:
   ```sql
   ALTER TABLE `categories` DROP COLUMN `sort`;
   ```

2. Remove upload directory (optional):
   ```bash
   rm -rf public/uploads/ckeditor
   ```

3. Revert all file changes using git or manual backup restore

---

---

## Step-by-Step Application Guide

### If you're applying changes to a fresh/backup installation:

1. **Database:**
   ```sql
   ALTER TABLE `categories` ADD COLUMN `sort` INT(11) DEFAULT 0 AFTER `show_on_home`;
   ```

2. **Create Directory:**
   ```bash
   mkdir -p public/uploads/ckeditor
   ```

3. **Apply File Changes:**
   - Follow each section above to update the files
   - Or replace the entire folders: app/, resources/, public/, routes/

4. **Verify:**
   - Check admin panel - Category form should have Sort Order field
   - Check Product form - CKEditor should load
   - Test image upload in CKEditor
   - Test sidebar menu with subcategories

---

**End of Changes Document**

