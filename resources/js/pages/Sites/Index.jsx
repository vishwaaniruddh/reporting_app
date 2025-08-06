import { Head } from '@inertiajs/react';
import { useState } from 'react';
import SiteTable from './Components/SiteTable';
import SearchField from '@/Components/SearchField';
import Pagination from '@/Components/Pagination';

export default function Index({ sites, filters }) {
    const [search, setSearch] = useState(filters.search || '');

    return (
        <>
            <Head title="Sites" />
            <div className="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <div className="px-4 py-6 sm:px-0">
                    <div className="flex justify-between items-center mb-6">
                        <h1 className="text-2xl font-semibold text-gray-900">Sites</h1>
                        <div className="flex items-center space-x-4">
                            <SearchField
                                value={search}
                                onChange={setSearch}
                                placeholder="Search sites..."
                            />
                        </div>
                    </div>
                    
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <SiteTable sites={sites.data} />
                    </div>

                    <div className="mt-6">
                        <Pagination links={sites.links} />
                    </div>
                </div>
            </div>
        </>
    );
}
