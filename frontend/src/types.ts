// src/types.ts

export interface Product {
    id: string;
    name: string;
    description?: string; 
    price: number;
    date_add?: string;
    created_at: string
}
