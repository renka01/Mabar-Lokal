<?php
/**
 * City Combobox Component
 * Requires: .city-combobox JS logic in app.js
 */
?>
<div class="city-combobox relative">
  <div class="flex h-11 w-full items-center rounded-md border border-border bg-card-alt px-3 transition-colors focus-within:border-red hover:border-muted focus-within:border-red">
    <i class="ph-fill ph-map-pin text-lg mr-4 text-muted shrink-0" style="margin-right: 8px;"></i>
    <input
      type="text"
      class="city-combobox-input w-full bg-transparent text-[13px] text-white outline-none placeholder:text-muted"
      placeholder="Cari kota..."
      autocomplete="off"
    />
    <i class="ph ph-caret-down ml-1 shrink-0 text-muted city-combobox-caret" style="transition: transform 0.2s ease;"></i>
  </div>
  <ul
    class="city-combobox-list absolute left-0 right-0 hidden rounded-md border border-border bg-card-alt py-1 shadow-xl"
    role="listbox"
    style="z-index: 9999; margin-top: 4px; max-height: 180px; overflow-y: auto; scrollbar-width: thin; scrollbar-color: #253142 transparent;"
  ></ul>
</div>
