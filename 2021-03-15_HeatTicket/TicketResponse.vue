<template>
    <div>
        <div class="row wrapper page-heading">
            <div class="col-lg-12">
                <div class="page_head_sec">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row justify-content-start">
                                <div class="col-sm-12">
                                    <h2>Responses <span class="badge">{{projects.length}}</span>
                                    </h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row justify-content-end">
                                <div class="col-sm-12 text-right">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row wrapper page-heading">
            <div class="col-lg-12">
                <div class="page_head_sec page_head_sec2">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
                            <div class="row justify-content-start">
                              
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-4 col-xs-12">
                            <div class="row doctr_list">
                               
                                <a href="" @click.prevent="box_visible(true, 'invisible')"
                                class="btn btn-primary click-action-filter mr-1" id="btn_reply_ticket">
                                    Reply to this Ticket <i class="fa fa-mail-reply"></i>
                                </a>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox ">
                        <div class="ibox-title drop_ttl d-flex justify-content-between">
                            <h5>
                                Response List
                            </h5>
                        </div>
                        <div class="ibox-content">
                            <div class="projects table-responsive nowrap">
                             
                               <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
                                    <tbody>
                                        <tr v-if="paginated.length==0">
                                            <td :colspan="columns.length" class="text-center">No data available in table</td> 
                                        </tr>
                                        <tr v-for="project in paginated" :key="project.id">
                                            <td >{{project.id}} </td>
                                            <td v-if="project.user != null">
                                                {{ project.user.first_name + ' ' + project.user.last_name }}
                                            </td>
                                            <td v-else>Admin</td>
                                            <td>{{project.reply_on}}</td>
                                            <td>{{project.message}}</td>
                                            <td>
                                            <template v-for="(att, index) in project.attachments" >
                                                <a :href="pathResponse+ '/' + att.name" :key="att.id"
                                                    target="blank"> 0{{index + 1}} </a>
                                            </template>
                                            </td>
                                            <td v-html="project.action"></td>
                                        </tr>
                                    </tbody>
                                </datatable>
                                <pagination :pagination="pagination" :client="true" :filtered="filteredProjects"
                                            @prev="--pagination.currentPage"
                                            @next="++pagination.currentPage">
                                </pagination>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</template>

<script>
import Datatable from './Datatable.vue';
import Pagination from './Pagination.vue';
export default {
    props: ['pathResponse'],
    components: { datatable: Datatable, pagination: Pagination },
    created() {
         this.getProjects();
         this.box_visible(false, 'visible');
    },
    data() {
        let sortOrders = {};

         let columns = [
            {width: '5%', label: 'ID', name: 'id' , type:'number'},
            {width: '18%', label: 'User', name: 'last_name', type:'html'},
            {width: '18%', label: 'Reply On', name: 'reply_on'},
            {width: '55%', label: 'Message', name: 'message'},
            {width: '25%', label: 'Attachment', name: 'attachment'},
            {width: '10%', label: 'Action', name: 'action'},
        ];

        columns.forEach((column) => {
           sortOrders[column.name] = 1;
        });
        return {
            projects: [],
            opened: [],
            roles:[],
            page_open:0,
            page_search:0,
            header:[],
            columns: columns,
            sortKey: 'last_name',
            sortOrders: sortOrders,
            length: 200,
            search: '',
            search2: '',
            tableData: {
                client: true,
            },
            pagination: {
                currentPage: 1,
                total: '',
                nextPage: '',
                prevPage: '',
                from: '',
                to: '',
                pages:[],
                firstPage:1,
                lastPage:'',
            },
            close_reply: false
        }
    },
    methods: {
        getProjects(url = window.location.href) {
            axios.get(url, {params: this.tableData})
                .then(response => {
                    this.projects = response.data;
                    this.pagination.total = this.projects.length;
                })
                .catch(errors => {
                    console.log(errors);
                });
        },
        paginate(array, length, pageNumber) {
            this.pagination.from = array.length ? ((pageNumber - 1) * length) + 1 : ' ';
            this.pagination.to = pageNumber * length > array.length ? array.length : pageNumber * length;
            this.pagination.prevPage = pageNumber > 1 ? pageNumber : '';
            this.pagination.nextPage = array.length > this.pagination.to ? pageNumber + 1 : '';

            this.pagination.pages =[];
            let $range = 2;
            let numberOfPages = Math.ceil(array.length / length);
            this.pagination.lastPage = numberOfPages;
            
            for (let $x = (this.pagination.currentPage - $range); $x < ((this.pagination.currentPage + $range) + 1); $x++) {
               if (($x > 0) && ($x <= numberOfPages)) {
                    this.pagination.pages.push($x);     
                }
            }
            //get from url param
            let url = window.location.href;
        
            var queryStart = url.indexOf("?") + 1,
                queryEnd   = url.indexOf("#") + 1 || url.length + 1,
                query = url.slice(queryStart, queryEnd - 1),
                pairs = query.replace(/\+/g, " ").split("&"),
                parms = {}, i, n, v, nv;
            
            for (i = 0; i < pairs.length; i++) {
                nv = pairs[i].split("=", 2);
                n = decodeURIComponent(nv[0]);
                v = decodeURIComponent(nv[1]);

                if (!parms.hasOwnProperty(n)) parms[n] = [];
                parms[n].push(nv.length === 2 ? v : null);
            }
            if (this.page_open < 1) {
                this.page_open = this.page_open+1;
                if (parms['filter']!=null) {
                    this.search=parms['filter'][0];
                }
                
            }
            
            return array.slice((pageNumber - 1) * length, pageNumber * length);
        },
        resetPagination() {
            this.pagination.currentPage = 1;
            this.pagination.prevPage = '';
            this.pagination.nextPage = '';
        },
        sortBy(key) {
            this.resetPagination();
            this.sortKey = key;
            this.sortOrders[key] = this.sortOrders[key] * -1;
        },
        getIndex(array, key, value) {
            return array.findIndex(i => i[key] == value)
        },
        box_visible(display = false, btn_display='visible') {
            var box = document.getElementById('box_reply_ticket');
            var btn_reply_ticket = document.getElementById('btn_reply_ticket');
            if (display == false) {
                box.style.display = 'none';
                this.close_reply = false;
            } else {
                if(btn_display == 'invisible') btn_reply_ticket.style.display = 'none';
                box.style.display = 'inherit';
                this.close_reply = true;
            }
        }
    },
    computed: {
        filteredProjects() {
            let projects = this.projects;
            if (this.page_search > 0) {
                if (this.search) {
                    projects = projects.filter((row) => {
                        return Object.keys(row).some((key) => {
                            return String(row[key]).toLowerCase().indexOf(this.search.toLowerCase()) > -1;
                        })
                    });
                }
            }
            let sortKey = this.sortKey;
            let order = this.sortOrders[sortKey] || 1;
            if (sortKey) {
                projects = projects.slice().sort((a, b) => {
                    let index = this.getIndex(this.columns, 'name', sortKey);
                    a = String(a[sortKey]).toLowerCase();
                    b = String(b[sortKey]).toLowerCase();
                    if (this.columns[index].type && this.columns[index].type === 'date') {
                        return (a === b ? 0 : new Date(a).getTime() > new Date(b).getTime() ? 1 : -1) * order;
                    } else if (this.columns[index].type && this.columns[index].type === 'number') {
                        return (+a === +b ? 0 : +a > +b ? 1 : -1) * order;
                    }else if (this.columns[index].type && this.columns[index].type === 'html') {
                        return (a === b ? 0 : a.toString().replace(/<[^>]*>/g, '') > b.toString().replace(/<[^>]*>/g, '') ? 1 : -1) * order;
                    } else {
                        return (a === b ? 0 : a > b ? 1 : -1) * order;
                    }
                });
            }
            return projects;
        },
        paginated() {
            return this.paginate(this.filteredProjects, this.length, this.pagination.currentPage);
        }
    }
};
</script>
