----------------------
views/layout/head.php
----------------------
-Get menu parent list from list of menu access
-If menu parent is in the menu parent list, display the parent menu

---------------------------
views/pengaturan/access.php
---------------------------
foreach($menu as $smn) { 
	if($smn->menu_parent == $mn->id_menu){, add:
$check = $this->db->where('id_jabatan',$role->id_jabatan)->where('id_menu',$smn->id_menu)->get('tb_user_access');
(previously it didn't check for the menu children)


----------------------
views/layout/template_gc.php
----------------------
-Allow kasir to create SO


------
views/goods_in_edit.php
------
Commented "Pilih Supplier" option to prevent changing supplier to empty

----
views/purchase_order_edit.php and sales_order_edit.php
----
Removed buttons if check = 1, used javascript to disable input if check = 1


----
views/transaksi/sales_order_create.php and sales_order_edit.php
----
!!TOLONG DIPERHATIKAN KEMBALI SAAT MELAKUKAN UPDATE, AGAR TIDAK MENG-OVERWRITE UPDATE YANG SUDAH PERNAH DILAKUKAN SEBELUMNYA
Client complain bahwa diskon tidak berfungsi saat create/edit SO, setelah saya cek, ternyata update yang sudah pernah saya lakukan sebelumnya
untuk perhitungan diskon pada kedua file diatas hilang (tertumpuk), sehingga saya harus mencari backup dari bulan Juni dan menyisipkan kembali
prosedur perhitungan diskon

-----------
controllers/Master.php
-----------
add access check to every main function

-----------
controllers/Item.php
-----------
add access check to every main function

------------
controllers/Ci.php
------------
function cash() <--- fix typo: callback_afet_update

-----------
controllers/Po.php
-----------
add access check to main function

function purchase_order: jangan tampilkan delete kalau bukan owner, tampilkan edit untuk semua (tapi selain owner & KT nanti check = 1), lalu retur hanya untuk owner & KT sementara

purchase_order_edit: added "AND B.id_item_masuk NOT IN (SELECT X.id_item_masuk FROM tb_item_masuk X WHERE X.deleted = 1)" on $check query to exclude deleted GIs

purchase_order_edit: if jabatan is not owner or KT, $check is immediately = 1 to prevent other people from editing

delete_purchase_order: ubah sistem validasi menjadi cek GI dan retur deleted saja, seandainya belum diapprove pun jangan boleh dihapus (karena GI akan tertinggal di database)

fix typo di message "untuk mengahpus Purchase Order"

-----------
controllers/Gi.php
-----------
add access check to main function

Fix fetch PO query on goods_in_create (approved_1 > 0 instead if approved_1 = 1, because it is filled with the user_id of the approver)

Fix function check_po: approved_1 > 0 instead if approved_1 = 1

check_po: kalau id_supplier = null, return json kosong (jadi harus memilih supplier dulu)

Fix is_approved function <-- approved_1 > 0 instead of approved_1 == 1

goods_in_create and check_po: added "AND B.id_item_masuk NOT IN (SELECT X.id_item_masuk FROM tb_item_masuk X WHERE X.deleted = 1)" in all SELECT DISTINCT subqueries so POs with deleted GIs will reappear when creating new GI

goods_in_create: setelah selesai redirect ke daftar goods in

function returStok <-- History Stok terbalik, reversed the stock flow and fixed the keterangan

Commented Edit action because of incorrect flow, check notes

function goods_in: unset delete kalau bukan owner

-----------
controllers/So.php
-----------
add access check to main function

function sales_order: jangan tampilkan delete kalau bukan owner, tampilkan edit untuk semua (tapi selain owner & KT nanti check = 1), lalu retur hanya untuk owner & KT sementara

sales_order_edit: added "AND B.id_item_keluar NOT IN (SELECT X.id_item_keluar FROM tb_item_keluar X WHERE X.deleted = 1)" on $check query to exclude deleted GOs
--FIXED THE QUERY IN GENERAL, USING PARENTHESIS: ...WHERE (EXISTS A OR EXISTS B) AND... <-- this is accurate on SO, but somehow if done on PO it's the reverse
--USE LESS AMBIGUOUS QUERY NEXT TIME!

sales_order_edit: if jabatan is not owner or KT, $check is immediately = 1 to prevent other people from editing

delete_sales_order: ubah sistem validasi menjadi cek GO dan retur deleted saja, seandainya belum diapprove pun jangan boleh dihapus (karena retur akan tertinggal di database)

fix typo di message "untuk mengahpus Sales Order"

sales_order_update() <-- fixed error <-- variable $total_diskon was not declared. Redirection was not working.

-----------
controllers/Go.php
-----------
add access check to main function

check_so: kalau id_gudang = null, return json kosong (jadi harus memilih gudang dulu)

goods_out_create: setelah selesai redirect ke daftar goods out

goods_out_create and check so: added "AND B.id_item_keluar NOT IN (SELECT X.id_item_keluar FROM tb_item_keluar X WHERE X.deleted = 1)" in all SELECT DISTINCT subqueries so SOs with deleted GOs will reappear when creating new GO

Commented Edit action because of (possible) incorrect flow, check notes

function goods_out: unset delete kalau bukan owner

---------

NOTE:
Edit GI -> Dicoba pada posisi 1 GI tersimpan, lalu dihilangkan centangnya, setelah disimpan tidak hilang
Edit GO -> ajax error
purchase_order_edit <-- tolong diperhatikan! saya sudah update untuk tampilan sesuai kebutuhan, tapi di update kemarin di revert kembali untuk implemen poin pengecekan retur / GI.
VALIDATION / YES/NO POPUP WHEN DELETING WITH CALLBACK!
!!EDITING PO & SO <-- SHOULD BE ALLOWED TO JUST CHANGE THE COMBOBOXES FOR OWNER/KT, JUST LOCK THE CONTENTS!