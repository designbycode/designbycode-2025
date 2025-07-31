import {Livewire} from '../../../vendor/livewire/livewire/dist/livewire.esm';

export default function imageConverter() {
    console.log('imageEditor init');
    return {
        showOriginal: false,

        handleDrop(event) {
            console.log('handleDrop');
            const file = event.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                Livewire.first().upload('image', file)
            }
        },

        resetFilters() {
            Livewire.first().set('sepiaValue', 0);
            Livewire.first().set('grayscaleValue', 0);
            Livewire.first().set('blurValue', 0);
            Livewire.first().set('brightnessValue', 100);
            Livewire.first().set('contrastValue', 100);
            Livewire.first().set('saturateValue', 100);
            Livewire.first().set('hueRotateValue', 0);
            Livewire.first().set('pixelateValue', 0);
        },

        getFilterStyle() {
            const filterString = [
                `sepia(${Livewire.first().get('sepiaValue')}%)`,
                `grayscale(${Livewire.first().get('grayscaleValue')}%)`,
                `blur(${Livewire.first().get('blurValue')}px)`,
                `brightness(${Livewire.first().get('brightnessValue')}%)`,
                `contrast(${Livewire.first().get('contrastValue')}%)`,
                `saturate(${Livewire.first().get('saturateValue')}%)`,
                `hue-rotate(${Livewire.first().get('hueRotateValue')}deg)`
            ].join(' ');

            let style = `filter: ${filterString};`;

            if (Livewire.first().get('pixelateValue') > 0) {
                const scale = Math.max(0.1, 1 - (Livewire.first().get('pixelateValue') / 100));
                style += ` transform: scale(${scale}); transform-origin: top left;`;
            }

            return style;
        },

        applyPreset(preset) {
            switch (preset) {
                case 'vintage':
                    Livewire.first().set('sepiaValue', 80);
                    Livewire.first().set('brightnessValue', 110);
                    Livewire.first().set('contrastValue', 120);
                    break;
                case 'bw':
                    Livewire.first().set('grayscaleValue', 100);
                    Livewire.first().set('contrastValue', 130);
                    break;
                case 'vivid':
                    Livewire.first().set('saturateValue', 150);
                    Livewire.first().set('contrastValue', 120);
                    Livewire.first().set('brightnessValue', 110);
                    break;
                case 'dreamy':
                    Livewire.first().set('blurValue', 3);
                    Livewire.first().set('brightnessValue', 120);
                    Livewire.first().set('saturateValue', 80);
                    break;
            }
        },
    }
}
