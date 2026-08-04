const fs = require('fs');

let file = fs.readFileSync('resources/js/Pages/SuperAdmin/Tenants/Index.jsx', 'utf8');

// 1. Remove assign button
file = file.replace(/<button[^>]+onClick=\{\(\) => openAssignModal\(tenant\)\}[^>]+>\s*????? ????????\s*<\/button>/g, '');

// 2. Add showPassword state
file = file.replace(/const \[createErrors, setCreateErrors\] = useState\(\{([^)]*)\}\);/, 'const [createErrors, setCreateErrors] = useState({});\n    const [showPassword, setShowPassword] = useState(false);');

// 3. Fix password input
let oldPasswordBlock = <label className="block text-sm font-semibold text-gray-700 mb-1">
                                        ???? ???? ??????
                                    </label>
                                    <input
                                        type="password"
                                        required
                                        value={createData.password}
                                        onChange={(e) => setCreateData({ ...createData, password: e.target.value })}
                                        placeholder="••••••••"
                                        dir="ltr"
                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all text-left"
                                    />;
let newPasswordBlock = <label className="block text-sm font-semibold text-gray-700 mb-1">
                                        ???? ???? ??????
                                    </label>
                                    <div className="relative">
                                        <input
                                            type={showPassword ? 'text' : 'password'}
                                            required
                                            value={createData.password}
                                            onChange={(e) => setCreateData({ ...createData, password: e.target.value })}
                                            placeholder="••••••••"
                                            dir="ltr"
                                            className="w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all text-left"
                                        />
                                        <button
                                            type="button"
                                            onClick={() => setShowPassword(!showPassword)}
                                            className="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none"
                                        >
                                            {showPassword ? (
                                                <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            ) : (
                                                <svg className="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                                </svg>
                                            )}
                                        </button>
                                    </div>;

file = file.replace(oldPasswordBlock, newPasswordBlock);

// 4. Change plan wording and add classes for arrow fixing
file = file.replace('<option value="">???? ???? ????</option>', '<option value="">???????? ???????</option>');

// Fix select CSS for Create Modal
let oldSelectCreate = className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all";
let newSelectCreate = style={{ backgroundImage: 'url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns=\\'http://www.w3.org/2000/svg\\' fill=\\'none\\' viewBox=\\'0 0 20 20\\'%3E%3Cpath stroke=\\'%236B7280\\' stroke-linecap=\\'round\\' stroke-linejoin=\\'round\\' stroke-width=\\'1.5\\' d=\\'m6 8 4 4 4-4\\'/%3E%3C/svg%3E")', backgroundPosition: 'left 0.75rem center', backgroundSize: '1.25rem', backgroundRepeat: 'no-repeat' }} className="w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all appearance-none";
file = file.replace(oldSelectCreate, newSelectCreate);

// Fix select CSS for Assign Modal
let oldSelectAssign = className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm";
let newSelectAssign = style={{ backgroundImage: 'url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns=\\'http://www.w3.org/2000/svg\\' fill=\\'none\\' viewBox=\\'0 0 20 20\\'%3E%3Cpath stroke=\\'%236B7280\\' stroke-linecap=\\'round\\' stroke-linejoin=\\'round\\' stroke-width=\\'1.5\\' d=\\'m6 8 4 4 4-4\\'/%3E%3C/svg%3E")', backgroundPosition: 'left 0.75rem center', backgroundSize: '1.25rem', backgroundRepeat: 'no-repeat' }} className="w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg text-sm appearance-none";
file = file.replace(oldSelectAssign, newSelectAssign);

fs.writeFileSync('resources/js/Pages/SuperAdmin/Tenants/Index.jsx', file);
