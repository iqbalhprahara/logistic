<div class="row">
    <div class="row mb-1">
        <div class="col-lg-6">
            <div class="row">
                <div class="col-lg-6">
                    <div class="mb-1 position-relative">
                        <label class="col-form-label col-form-label-sm">No. AWB</label>
                        <input type="text" class="form-control form-control-sm" placeholder="Auto Generated" value="{{ optional($awb)->awb_no }}" disabled>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-1 position-relative">
                        <label class="col-form-label col-form-label-sm">No. Ref</label>
                        <input type="text" class="form-control form-control-sm @error('awb.ref_no') is-invalid @enderror" placeholder="{{ $viewOnly ? '' : 'Misal: 150259' }}" wire:model.blur="awb.ref_no" @disabled($viewOnly)>

                        @error('awb.ref_no')
                        <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                    </div>
                </div>
            </div>
            <div class="mb-1 position-relative">
                <label class="col-form-label col-form-label-sm">Pelanggan<span class="text-danger">*</span></label>
                @if (optional($awb)->exists)
                    <input type="text" class="form-control form-control-sm" value="{{ $awb->client->user->name . ' - ' . $awb->client->company->name }}" disabled>
                @elseif (Auth::user()->isClient())
                    <input type="text" class="form-control form-control-sm" value="{{ Auth::user()->name . ' - ' . Auth::user()->company_name }}" disabled>
                @else
                    <select class="form-control form-control-sm @error('awb.client_uuid') is-invalid @enderror" wire:model.blur="awb.client_uuid" required>
                        @if ($clientOptions)
                        <option value="">-- Pilih Pelanggan --</option>
                        @foreach($clientOptions as $value => $text)
                        <option value="{{ $value }}">{{ $text }}</option>
                        @endforeach
                        @else
                            <option value="" disabled selected>Loading data ...</option>
                        @endif
                    </select>

                    @error('awb.client_uuid')
                    <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <h6 class="mt-3">Detail Pengirim</h6>
        <hr>
        <div class="row">
            <div class="col-lg-6">
                <div class="mb-1 position-relative">
                    <label class="col-form-label col-form-label-sm">Provinsi<span class="text-danger">*</span></label>
                    <select class="form-control form-control-sm @error('awb.origin_province_id') is-invalid @enderror" wire:model.blur="awb.origin_province_id" required @disabled($viewOnly)>
                        <option value="">-- Pilih Provinsi --</option>
                        @if ($originProvinceOptions)
                        @foreach($originProvinceOptions as $value => $text)
                        <option value="{{ $value }}">{{ $text }}</option>
                        @endforeach
                        @else
                            <option value="" disabled selected>Loading data ...</option>
                        @endif
                    </select>

                    @error('awb.origin_province_id')
                    <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mb-1 position-relative">
                    <label class="col-form-label col-form-label-sm">Kota<span class="text-danger">*</span></label>
                    <select class="form-control form-control-sm @error('awb.origin_city_id') is-invalid @enderror" wire:model.blur="awb.origin_city_id" required @disabled($viewOnly)>
                        <option value="">-- Pilih Kota --</option>
                        @if ($originCityOptions)
                        @foreach($originCityOptions as $value => $text)
                        <option value="{{ $value}}">{{ $text }}</option>
                        @endforeach
                        @endif
                    </select>

                    @error('awb.origin_city_id')
                    <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="mb-1 position-relative">
                    <label class="col-form-label col-form-label-sm">Kecamatan<span class="text-danger">*</span></label>
                    <select class="form-control form-control-sm @error('awb.origin_subdistrict_id') is-invalid @enderror" wire:model.blur="awb.origin_subdistrict_id" required @disabled($viewOnly)>
                        <option value="">-- Pilih Kecamatan --</option>
                        @if ($originSubdistrictOptions)
                        @foreach($originSubdistrictOptions as $value => $text)
                        <option value="{{ $value}}">{{ $text }}</option>
                        @endforeach
                        @endif
                    </select>

                    @error('awb.origin_subdistrict_id')
                    <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mb-1 position-relative">
                    <label class="col-form-label col-form-label-sm">Kode Pos</label>
                    <input type="text" class="form-control form-control-sm @error('awb.origin_zipcode') is-invalid @enderror" wire:model.blur="awb.origin_zipcode" maxlength="5" @disabled($viewOnly)>
                    @error('awb.origin_zipcode')
                    <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="mb-1 position-relative">
            <label class="col-form-label col-form-label-sm">Alamat 1<span class="text-danger">*</span></label>
            <textarea class="form-control form-control-sm @error('awb.origin_address_line1') is-invalid @enderror" wire:model.blur="awb.origin_address_line1" required @disabled($viewOnly)></textarea>
            @error('awb.origin_address_line1')
            <div class="invalid-tooltip">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-1 position-relative">
            <label class="col-form-label col-form-label-sm">Alamat 2</label>
            <textarea class="form-control form-control-sm @error('awb.origin_address_line2') is-invalid @enderror" wire:model.blur="awb.origin_address_line2" @disabled($viewOnly)></textarea>
            @error('awb.origin_address_line2')
            <div class="invalid-tooltip">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-1 position-relative">
            <label class="col-form-label col-form-label-sm">Nama Pengirim<span class="text-danger">*</span></label>
            <input type="text" class="form-control form-control-sm @error('awb.origin_contact_name') is-invalid @enderror" wire:model.blur="awb.origin_contact_name" required @disabled($viewOnly)>
            @error('awb.origin_contact_name')
            <div class="invalid-tooltip">{{ $message }}</div>
            @enderror
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="mb-1 position-relative">
                    <label class="col-form-label col-form-label-sm">Telepon<span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm @error('awb.origin_contact_phone') is-invalid @enderror" wire:model.blur="awb.origin_contact_phone" minlength="8" maxlength="13" required @disabled($viewOnly)>
                    @error('awb.origin_contact_phone')
                    <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mb-1 position-relative">
                    <label class="col-form-label col-form-label-sm">Telepon 2</span></label>
                    <input type="text" class="form-control form-control-sm @error('awb.origin_contact_alt_phone') is-invalid @enderror" wire:model.blur="awb.origin_contact_alt_phone" minlength="8" maxlength="13" @disabled($viewOnly)>
                    @error('awb.origin_contact_alt_phone')
                    <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <h6 class="mt-3">Detail Penerima</h6>
        <hr>
        <div class="row">
            <div class="col-lg-6">
                <div class="mb-1 position-relative">
                    <label class="col-form-label col-form-label-sm">Provinsi<span class="text-danger">*</span></label>
                    <select class="form-control form-control-sm @error('awb.destination_province_id') is-invalid @enderror" wire:model.blur="awb.destination_province_id" required @disabled($viewOnly)>
                        <option value="">-- Pilih Provinsi --</option>
                        @if ($destinationProvinceOptions)
                        @foreach($destinationProvinceOptions as $value => $text)
                        <option value="{{ $value}}">{{ $text }}</option>
                        @endforeach
                        @else
                            <option value="" disabled selected>Loading data ...</option>
                        @endif
                    </select>
                    @error('awb.destination_province_id')
                    <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mb-1 position-relative">
                    <label class="col-form-label col-form-label-sm">Kota<span class="text-danger">*</span></label>
                    <select class="form-control form-control-sm @error('awb.destination_city_id') is-invalid @enderror" wire:model.blur="awb.destination_city_id" required @disabled($viewOnly)>
                        <option value="">-- Pilih Kota --</option>
                        @if ($destinationCityOptions)
                        @foreach($destinationCityOptions as $value => $text)
                        <option value="{{ $value}}">{{ $text }}</option>
                        @endforeach
                        @endif
                    </select>
                    @error('awb.destination_city_id')
                    <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="mb-1 position-relative">
                    <label class="col-form-label col-form-label-sm">Kecamatan<span class="text-danger">*</span></label>
                    <select class="form-control form-control-sm @error('awb.destination_subdistrict_id') is-invalid @enderror" wire:model.blur="awb.destination_subdistrict_id" required @disabled($viewOnly)>
                        <option value="">-- Pilih Kecamatan --</option>
                        @if ($destinationSubdistrictOptions)
                        @foreach($destinationSubdistrictOptions as $value => $text)
                        <option value="{{ $value}}">{{ $text }}</option>
                        @endforeach
                        @endif
                    </select>
                    @error('awb.destination_subdistrict_id')
                    <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mb-1 position-relative">
                    <label class="col-form-label col-form-label-sm">Kode Pos</label>
                    <input type="text" class="form-control form-control-sm @error('awb.destination_zipcode') is-invalid @enderror" wire:model.blur="awb.destination_zipcode" maxlength="5" @disabled($viewOnly)>
                    @error('awb.destination_zipcode')
                    <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="mb-1 position-relative">
            <label class="col-form-label col-form-label-sm">Alamat 1<span class="text-danger">*</span></label>
            <textarea class="form-control form-control-sm @error('awb.destination_address_line1') is-invalid @enderror" wire:model.blur="awb.destination_address_line1" required @disabled($viewOnly)></textarea>
            @error('awb.destination_address_line1')
            <div class="invalid-tooltip">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-1 position-relative">
            <label class="col-form-label col-form-label-sm">Alamat 2</label>
            <textarea class="form-control form-control-sm @error('awb.destination_address_line2') is-invalid @enderror" wire:model.blur="awb.destination_address_line2" @disabled($viewOnly)></textarea>
            @error('awb.destination_address_line2')
            <div class="invalid-tooltip">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-1 position-relative">
            <label class="col-form-label col-form-label-sm">Nama Penerima<span class="text-danger">*</span></label>
            <input type="text" class="form-control form-control-sm @error('awb.destination_contact_name') is-invalid @enderror" wire:model.blur="awb.destination_contact_name" required @disabled($viewOnly)>
            @error('awb.destination_contact_name')
            <div class="invalid-tooltip">{{ $message }}</div>
            @enderror
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="mb-1 position-relative">
                    <label class="col-form-label col-form-label-sm">Telepon<span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm @error('awb.destination_contact_phone') is-invalid @enderror" wire:model.blur="awb.destination_contact_phone" minlength="8" maxlength="13" required @disabled($viewOnly)>
                    @error('awb.destination_contact_phone')
                    <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mb-1 position-relative">
                    <label class="col-form-label col-form-label-sm">Telepon 2</span></label>
                    <input type="text" class="form-control form-control-sm @error('awb.destination_contact_alt_phone') is-invalid @enderror" minlength="8" maxlength="13" wire:model.blur="awb.destination_contact_alt_phone" @disabled($viewOnly)>
                    @error('awb.destination_contact_alt_phone')
                    <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <h6 class="mt-3">Detail Pengiriman</h6>
        <hr>
        <div class="row">
            <div class="col-lg-6">
                <div class="mb-1 position-relative">
                    <label class="col-form-label col-form-label-sm">Transportasi Pickup<span class="text-danger">*</span></label>
                    <select class="form-control form-control-sm @error('awb.transportation_id') is-invalid @enderror" wire:model.blur="awb.transportation_id" required @disabled($viewOnly)>
                        @if ($transportationOptions)
                        @foreach($transportationOptions as $value => $text)
                        <option value="{{ $value}}">{{ $text }}</option>
                        @endforeach
                        @else
                            <option value="" disabled selected>Loading data ...</option>
                        @endif
                    </select>
                    @error('awb.transportation_id')
                    <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mb-1 position-relative">
                    <label class="col-form-label col-form-label-sm">Jenis Kiriman<span class="text-danger">*</span></label>
                    <select class="form-control form-control-sm @error('awb.shipment_type_id') is-invalid @enderror" wire:model.blur="awb.shipment_type_id" required @disabled($viewOnly)>
                        @if ($shipmentTypeOptions)
                        @foreach($shipmentTypeOptions as $value => $text)
                        <option value="{{ $value}}">{{ $text }}</option>
                        @endforeach
                        @else
                            <option value="" disabled selected>Loading data ...</option>
                        @endif
                    </select>
                    @error('awb.shipment_type_id')
                    <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="mb-1 position-relative">
                    <label class="col-form-label col-form-label-sm">Jenis Layanan<span class="text-danger">*</span></label>
                    <select class="form-control form-control-sm @error('awb.service_id') is-invalid @enderror" wire:model.blur="awb.service_id" required @disabled($viewOnly)>
                        @if ($serviceOptions)
                        @foreach($serviceOptions as $value => $text)
                        <option value="{{ $value}}">{{ $text }}</option>
                        @endforeach
                        @else
                            <option value="" disabled selected>Loading data ...</option>
                        @endif
                    </select>
                    @error('awb.service_id')
                    <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mb-1 position-relative">
                    <label class="col-form-label col-form-label-sm">Tambah Packing</label>
                    <select class="form-control form-control-sm @error('awb.packing_id') is-invalid @enderror" wire:model.blur="awb.packing_id" @disabled($viewOnly)>
                        <option value="">-- Pilih Packing</option>
                        @if ($packingOptions)
                        @foreach($packingOptions as $value => $text)
                        <option value="{{ $value}}">{{ $text }}</option>
                        @endforeach
                        @else
                            <option value="" disabled selected>Loading data ...</option>
                        @endif
                    </select>
                    @error('awb.packing_id')
                    <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <h6 class="mt-3">Detail Barang</h6>
        <hr>
        <div class="row">
            <div class="col-lg-6">
                <div class="mb-1 position-relative">
                    <label class="col-form-label col-form-label-sm">Total Koli<span class="text-danger">*</span></label>
                    <input type="number" class="form-control form-control-sm @error('awb.package_koli') is-invalid @enderror" wire:model.blur="awb.package_koli" min="1" required @disabled($viewOnly)>
                    @error('awb.package_koli')
                    <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mb-1 position-relative">
                    <label class="col-form-label col-form-label-sm">Total Berat(Kg)<span class="text-danger">*</span></label>
                    <input type="number" class="form-control form-control-sm @error('awb.package_weight') is-invalid @enderror" wire:model.blur="awb.package_weight" min="1" required @disabled($viewOnly)>
                    @error('awb.package_weight')
                    <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="mb-1 position-relative">
                    <label class="col-form-label col-form-label-sm">Volumetric<span class="text-danger">*</span></label>
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">P</label>
                        <input type="number" class="form-control form-control-sm @error('awb.package_length') is-invalid @enderror" wire:model.blur="awb.package_length" min="1" required @disabled($viewOnly)>
                    </div>
                    @error('awb.package_length')
                    <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">L</label>
                        <input type="number" class="form-control form-control-sm @error('awb.package_width') is-invalid @enderror" wire:model.blur="awb.package_width" min="1" required @disabled($viewOnly)>
                    </div>
                    @error('awb.package_width')
                    <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                    <div class="input-group input-group-sm mb-1">
                        <label class="input-group-text">T</label>
                        <input type="number" class="form-control form-control-sm @error('awb.package_height') is-invalid @enderror" wire:model.blur="awb.package_height" min="1" required @disabled($viewOnly)>
                    </div>
                    @error('awb.package_height')
                    <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mb-1 position-relative">
                    <label class="col-form-label col-form-label-sm">Deskripsi Barang</label>
                    <textarea class="form-control form-control-sm @error('awb.package_desc') is-invalid @enderror" wire:model.blur="awb.package_desc" @disabled($viewOnly)></textarea>
                    @error('awb.package_description')
                    <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="mb-1 position-relative">
                    <label class="col-form-label col-form-label-sm">COD ?<span class="text-danger"></label>
                    <select class="form-control form-control-sm @error('awb.is_cod') is-invalid @enderror" wire:model.blur="awb.is_cod" required @disabled($viewOnly)>
                        <option value="false">No</option>
                        <option value="true">Yes</option>
                    </select>
                    @error('awb.is_cod')
                    <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mb-1 position-relative">
                    <label class="col-form-label col-form-label-sm">Nilai Barang</label>
                    <input type="number" class="form-control form-control-sm @error('awb.package_value') is-invalid @enderror" wire:model.blur="awb.package_value" min="0" @disabled($viewOnly)>
                    @error('awb.package_value')
                    <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="mb-1 position-relative">
                    <label class="col-form-label col-form-label-sm">Asuransi<span class="text-danger"></label>
                    <select class="form-control form-control-sm @error('awb.is_insurance') is-invalid @enderror" wire:model.blur="awb.is_insurance" required @disabled($viewOnly)>
                        <option value="false">No</option>
                        <option value="true">Yes</option>
                    </select>
                    @error('awb.is_insurance')
                    <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mb-1 position-relative">
                    <label class="col-form-label col-form-label-sm">Instruksi Khusus</label>
                    <textarea class="form-control form-control-sm @error('awb.package_instruction') is-invalid @enderror" wire:model.blur="awb.package_instruction" @disabled($viewOnly)></textarea>
                    @error('awb.package_instruction')
                    <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>
