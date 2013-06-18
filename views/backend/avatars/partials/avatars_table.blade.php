<table border="0" class="table table-bordered">
    <thead>
        <tr>
            <th width="10%">{{ __('osregistration::lang.#')->get(ADM_LANG) }}</th>
            <th>{{ __('osregistration::lang.Name')->get(ADM_LANG) }}</th>
            <th>{{ __('osregistration::lang.Description')->get(ADM_LANG) }}</th>
            <th>{{ __('osregistration::lang.Status')->get(ADM_LANG) }}</th>
            <th width="200" class="collapse"></th>
        </tr>
    </thead>
    <tbody class="sortable">
    @if(isset($custom_avatars) and !empty($custom_avatars))
    @foreach($custom_avatars as $custom_avatar)
        <tr id="avatar-id-{{$custom_avatar->id}}" class="handle" data-id="{{ $custom_avatar->id }}">
            <td>
                <?php echo \Thumbnails\Html::thumbnail($custom_avatar->image_absolute_path.DS.$custom_avatar->image_full_name,
                    array(
                        'mode' => 'outbound',
                        'size' => '100x100',
                        'alt'  => 'avatar-'.$custom_avatar->id,
                        'attr' => array(
                            'data-avatar-id' => $custom_avatar->id, 
                            'style'          => 'width: 100; height: 100;'),
                        )
                    ) 
                ?>
            </td>
            <td class="align-center">{{ Str::title($custom_avatar->name) }}</td>

            <td class="align-center">{{ $custom_avatar->description }}</td>
            
            <td><?php if($custom_avatar->status == 0) {echo  __('osregistration::lang.Disabled')->get(ADM_LANG);} else { echo __('osregistration::lang.Enabled')->get(ADM_LANG); } ?></td>
            <td class="collapse actions">
                
                <a href="{{ URL::base() . '/'.ADM_URI.'/'}}osregistration/avatars/{{ $custom_avatar->id }}/edit" class="btn btn-mini"><i class="icon-edit"></i> {{ __('osregistration::lang.Edit')->get(ADM_LANG) }}</a>
                
                <a data-module="osregistration/avatars" data-verb="DELETE" data-title="{{ __('osregistration::lang.Are you sure to destroy the this custom avatar?')->get(ADM_LANG)}}" class="confirm btn btn-danger btn-mini delete" href="{{ URL::base().'/'.ADM_URI }}/osregistration/avatars/{{ $custom_avatar->id }}"><i class="icon-trash icon-white"></i> {{ Lang::line('osregistration::lang.Delete')->get(ADM_LANG) }}</a>
            </td>
        </tr>
    @endforeach
    @else
    <tr>
        <td colspan="5" style="text-align:center;">{{ __('osregistration::lang.No custom avatars were found')->get(ADM_LANG) }}</td>
    </tr>
    @endif
    </tbody>
</table>