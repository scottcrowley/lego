export default {
    props: {
        location_id: {
            type: String,
            default: ''
        }, 
        image_field: {
            type: String,
            default: ''
        }, 
        image_label_field: {
            type: String,
            default: ''
        }, 
        toggle_end_point: {
            type: String,
            default: ''
        }, 
        valnames: {
            type: Array,
            default: [{}]
        }, 
        allow_image_swap: {
            type: Boolean,
            default: false
        },
    },

    data() {
        return {
            pagerLimit: 2,
            pagerShowDisabled: true,
            pagerSize: 'small',
            pagerAlign: 'left',
        }
    },

    mounted() {
        let updateSort = this.checkUriParams();
        if (!updateSort) {
            this.checkSortDefault();
        }
        
        this.updateSortSelect();
        this.getResults();
    },

    methods: {
        updateSortSelect() {
            document.getElementById('selectSort').value = this.sortedCol;
            document.getElementById('selectOrder').value = (this.sortdesc) ? 1 : 0;
        },
        updateSort(event) {
            let value = event.target.value;
            
            if (value != '' && value != this.sortedCol) {
                this.sortedCol = value;
                let col = (this.sortdesc ? '-' : '') + value;
                this.sortCols = [col];
                this.getResults();
            }
        },
        updateSortOrder(event) {
            let value = (event.target.value == '1') ? true : false;
            
            if (value != this.sortdesc) {
                this.sortdesc = value;
                let col = (value ? '-' : '') + this.sortedCol;
                this.sortCols[0] = col;
                this.getResults();
            }
        },
        updatePerPage(event) {
            if (event.target.value == 0 || event.target.value == this.perpage) return;
            this.perpage = event.target.value;

            this.getResults(this.currentPage);
        },
        swapImageUrl(event) {
            if (this.allow_image_swap) {
                let src = event.target.src;
                let alt = event.target.dataset.altImage;

                event.target.src = alt;
                event.target.dataset.altImage = src;
            }
        },
    }
}